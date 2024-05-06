<?php

namespace App\Module\TSP\Application\Problem;

use App\Module\TSP\Domain\Constant;
use App\Module\TSP\Domain\ResultItem;
use App\Shared\Domain\DistanceMap;
use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;
use UnexpectedValueException;

/**
 * @see https://www.baeldung.com/java-ant-colony-optimization
 */
readonly class AntColonyOptimization
{
    private PheromoneMatrix $pheromoneMatrix;

    public function __construct(
        private AntStrategyInterface $antStrategy,
        private string $initialLocation,
        private DistanceMap $distanceMap,
        private float $alpha = Constant::DEFAULT_ALPHA,
        private float $beta = Constant::DEFAULT_BETA,
        private int $distanceCoefficient = Constant::DEFAULT_DISTANCE_COEFFICIENT,
        private float $evaporation = Constant::DEFAULT_EVAPORATION,
        private float $antFactor = Constant::DEFAULT_ANT_FACTOR,
        float $c = Constant::DEFAULT_PHEROMONE_INITIAL_VALUE,
    ) {
        $this->pheromoneMatrix = PheromoneMatrix::createFromLocations($distanceMap->keys(), $c);
    }

    /**
     * @return CollectionInterface<ResultItem>
     */
    public function optimize(int $iterations = Constant::DEFAULT_ITERATIONS): CollectionInterface
    {
        if ($iterations < 1) {
            throw new UnexpectedValueException('Iterations must be a positive number!');
        }

        $result = new Collection(ResultItem::class);

        for ($i = 0; $i < $iterations; ++$i) {
            $ants = $this->createAnts();

            foreach ($ants as $ant) {
                $ant->start();
                $result->add(new ResultItem(clone $ant->getPath(), $ant->getLength()));

                $this->updatePheromones($ant);
            }

            $this->evaporatePheromones();
        }

        return $result->sort('length');
    }

    public function getPheromones(): PheromoneMatrix
    {
        return clone $this->pheromoneMatrix;
    }

    /**
     * @return CollectionInterface<AntInterface>
     */
    private function createAnts(): CollectionInterface
    {
        $ants = new Collection(AntInterface::class);
        $numAnts = (int) ceil($this->distanceMap->count() * $this->antFactor);

        if ($numAnts < 1) {
            throw new UnexpectedValueException('Ants must be a positive number!');
        }

        for ($i = 0; $i < $numAnts; ++$i) {
            $ants->add($this->antStrategy->createAnt(
                initialLocation: $this->initialLocation,
                distanceMap: $this->distanceMap,
                pheromoneMatrix: $this->pheromoneMatrix,
                alpha: $this->alpha,
                beta: $this->beta,
                distanceCoefficient: $this->distanceCoefficient,
            ));
        }

        return $ants;
    }

    private function updatePheromones(AntInterface $ant): void
    {
        $path = $ant->getPath();
        $lengthCoefficient = $ant->getLength() / $this->distanceCoefficient;

        for ($i = 0; $i < $path->count() - 1; ++$i) {
            $this->pheromoneMatrix->increasePheromone($path[$i], $path[$i + 1], 1 / $lengthCoefficient);
            $this->pheromoneMatrix->increasePheromone($path[$i + 1], $path[$i], 1 / $lengthCoefficient);
        }
    }

    private function evaporatePheromones(): void
    {
        foreach ($this->pheromoneMatrix as $aId => $cols) {
            foreach ($cols as $bId => $value) {
                $this->pheromoneMatrix->updatePheromone($aId, $bId, $value * (1 - $this->evaporation));
            }
        }
    }
}
