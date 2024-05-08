<?php

namespace App\Module\TSP\Application\Optimal;

use App\Core\Infrastructure\Bus\SharedQueryBusInterface;
use App\Module\TSP\Application\Optimal\Event\OptimalParametersFoundEvent;
use App\Module\TSP\Application\Optimal\Event\ParametersTestedEvent;
use App\Module\TSP\Application\Optimal\Event\FindOptimalParametersStartedEvent;
use App\Module\TSP\Application\Optimal\VO\TestParametersVO;
use App\Module\TSP\Application\Optimal\VO\TestResultVO;
use App\Module\TSP\Application\Problem\AntColonyOptimization;
use App\Module\TSP\Application\Problem\AntStrategy\SalesmanAntStrategy;
use App\Shared\Application\Interaction\SharedQuery\AskForDistanceMapQuery;
use App\Shared\Domain\DistanceMap;
use App\Shared\Domain\LocationCollection;
use MathPHP\Exception\BadDataException;
use MathPHP\Statistics\Average;
use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;
use Ramsey\Collection\Map\TypedMap;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

readonly class OptimalParametersFinder
{
    public function __construct(
        private SharedQueryBusInterface $sharedQueryBus,
    ) {
    }

    /**
     * @return CollectionInterface<TestResultVO>
     *
     * @throws BadDataException
     */
    public function find(
        LocationCollection $locations,
        int $iterations,
        int $testAttemptsNum,
        EventDispatcherInterface $eventDispatcher,
    ): CollectionInterface {
        $distanceMap = $this->createDistanceMap($locations);
        $parameters = $this->generateParameters();
        $results = new Collection(TestResultVO::class);

        $eventDispatcher->dispatch(new FindOptimalParametersStartedEvent($parameters->count()));

        foreach ($parameters as $parameter) {
            $results->add($this->testParameters(
                $iterations,
                $testAttemptsNum,
                $parameter,
                $distanceMap,
                $locations->first()->id,
            ));

            $eventDispatcher->dispatch(new ParametersTestedEvent($parameter));
        }

        $sorted = $this->sortResults($results);
        $eventDispatcher->dispatch(new OptimalParametersFoundEvent($sorted));

        return $sorted;
    }

    /**
     * @throws BadDataException
     */
    private function testParameters(
        int $iterations,
        int $testAttemptsNum,
        TestParametersVO $parameters,
        DistanceMap $distanceMap,
        string $initialLocation,
    ): TestResultVO {
        $time = [];
        $length = [];
        $stopwatch = new Stopwatch();

        for ($i = 0; $i < $testAttemptsNum; ++$i) {
            $timeName = 'OptimalParametersFinder_test_' . $i;
            $stopwatch->start($timeName);

            $colony = new AntColonyOptimization(
                antStrategy: new SalesmanAntStrategy(),
                initialLocation: $initialLocation,
                distanceMap: $distanceMap,
                alpha: $parameters->alpha,
                beta: $parameters->beta,
                distanceCoefficient: $parameters->distanceCoefficient,
                evaporation: $parameters->evaporation,
                antFactor: $parameters->antFactor,
                c: $parameters->c,
            );

            $results = $colony->optimize($iterations);
            $event = $stopwatch->stop($timeName);

            $time[] = $event->getDuration();
            $length[] = $results->first()->length;
        }

        return new TestResultVO(
            meanTime: Average::mean($time),
            meanLength: Average::mean($length),
            iterations: $iterations,
            testAttemptsNum: $testAttemptsNum,
            parameters: $parameters,
        );
    }

    private function createDistanceMap(LocationCollection $locations): DistanceMap
    {
        return $this->sharedQueryBus->dispatch(new AskForDistanceMapQuery($locations));
    }

    /**
     * @return CollectionInterface<TestParametersVO>
     */
    private function generateParameters(): CollectionInterface
    {
        $collection = new Collection(TestParametersVO::class);
        $range = $this->createRange();

        foreach ($range->get('alpha') as $alpha) {
            foreach ($range->get('beta') as $beta) {
                foreach ($range->get('distanceCoefficient') as $distanceCoefficient) {
                    foreach ($range->get('evaporation') as $evaporation) {
                        foreach ($range->get('antFactor') as $antFactor) {
                            foreach ($range->get('c') as $c) {
                                $collection->add(new TestParametersVO(
                                    alpha: $alpha,
                                    beta: $beta,
                                    distanceCoefficient: $distanceCoefficient,
                                    evaporation: $evaporation,
                                    antFactor: $antFactor,
                                    c: $c,
                                ));
                            }
                        }
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * @return TypedMap<string, iterable<int|float>>
     */
    private function createRange(): TypedMap
    {
        /* @phpstan-ignore-next-line */
        return new TypedMap('string', 'array', [
            'alpha' => range(1.0, 2.5, 0.5),
            'beta' => range(2.0, 5.0, 0.5),
            'distanceCoefficient' => [1, ...range(50, 200, 50)],
            'evaporation' => [0.35, ...range(0.4, 0.8, 0.2)],
            'antFactor' => range(0.6, 1.0, 0.1),
            'c' => range(0.6, 1.0, 0.2),
        ]);
    }

    /**
     * @param CollectionInterface<TestResultVO> $results
     *
     * @return CollectionInterface<TestResultVO>
     */
    private function sortResults(CollectionInterface $results): CollectionInterface
    {
        $resultsArray = $results->toArray();

        usort($resultsArray, function (TestResultVO $a, TestResultVO $b): int {
            if ($a->meanLength == $b->meanLength) {
                return $a->meanTime <=> $b->meanTime;
            }

            return $a->meanLength <=> $b->meanLength;
        });

        return new Collection(TestResultVO::class, $resultsArray);
    }
}
