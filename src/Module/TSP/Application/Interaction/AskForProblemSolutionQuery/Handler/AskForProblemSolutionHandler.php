<?php

namespace App\Module\TSP\Application\Interaction\AskForProblemSolutionQuery\Handler;

use App\Core\Infrastructure\Bus\SharedQueryBusInterface;
use App\Module\TSP\Application\Interaction\AskForProblemSolutionQuery\AskForProblemSolutionQuery;
use App\Module\TSP\Application\Problem\AntColonyOptimization;
use App\Module\TSP\Application\Problem\AntStrategy\SalesmanAntStrategy;
use App\Module\TSP\UI\Dto\SolutionResultDto;
use App\Shared\Application\Interaction\SharedQuery\AskForDistanceMapQuery;
use App\Shared\Domain\DistanceMap;
use App\Shared\Domain\Location;
use App\Shared\Domain\LocationCollection;
use App\Shared\UI\Dto\LocationDto;
use PhpUnitConversion\Unit\Length\KiloMeter;
use Ramsey\Collection\CollectionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use OutOfRangeException;

readonly class AskForProblemSolutionHandler
{
    public function __construct(
        private SharedQueryBusInterface $sharedQueryBus,
    ) {
    }

    #[AsMessageHandler(bus: 'query_bus')]
    public function handle(AskForProblemSolutionQuery $query): SolutionResultDto
    {
        $locations = new LocationCollection(array_map(
            fn (LocationDto $dto) => $dto->toLocation(),
            $query->solveRequest->locations
        ));

        /** @var DistanceMap $distanceMap */
        $distanceMap = $this->sharedQueryBus->dispatch(new AskForDistanceMapQuery($locations));

        if (!isset($locations[$query->solveRequest->initialLocationIndex])) {
            throw new OutOfRangeException('Invalid initial location index!');
        }

        $colony = new AntColonyOptimization(
            antStrategy: new SalesmanAntStrategy(),
            initialLocation: $locations[$query->solveRequest->initialLocationIndex]->id,
            distanceMap: $distanceMap,
            alpha: $query->solveRequest->alpha,
            beta: $query->solveRequest->beta,
            distanceCoefficient: $query->solveRequest->distanceCoefficient,
            evaporation: $query->solveRequest->evaporation,
            antFactor: $query->solveRequest->antFactor,
            c: $query->solveRequest->c,
        );

        $results = $colony->optimize();

        return new SolutionResultDto(
            path: $this->createPath($results->first()->path, $locations),
            length: new KiloMeter($results->first()->length),
            distanceMap: $distanceMap,
        );
    }

    /**
     * @param CollectionInterface<string> $path
     *
     * @return Location[]
     */
    private function createPath(CollectionInterface $path, LocationCollection $locations): array
    {
        return array_map(fn (string $locationId) => $locations->findById($locationId), $path->toArray());
    }
}
