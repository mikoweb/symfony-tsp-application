<?php

namespace App\Module\TSP\Application\Problem;

use App\Module\TSP\Domain\Constant;
use App\Shared\Domain\DistanceMap;
use Ramsey\Collection\Collection;
use Ramsey\Collection\CollectionInterface;
use Ramsey\Collection\Map\TypedMap;
use Random\Randomizer;
use UnexpectedValueException;

/**
 * @see https://www.baeldung.com/java-ant-colony-optimization
 */
class SalesmanAnt implements AntInterface
{
    /**
     * @var CollectionInterface<string>
     */
    private CollectionInterface $path;
    private ?string $currentLocation;

    public function __construct(
        private readonly string $initialLocation,
        private readonly DistanceMap $distanceMap,
        private readonly PheromoneMatrix $pheromoneMatrix,
        private readonly float $alpha = Constant::DEFAULT_ALPHA,
        private readonly float $beta = Constant::DEFAULT_BETA,
        private readonly int $distanceCoefficient = Constant::DEFAULT_DISTANCE_COEFFICIENT,
    ) {
        $this->path = new Collection('string');
        $this->currentLocation = null;
    }

    public function start(): void
    {
        $this->applyInitialLocation();

        while ($this->path->count() < $this->distanceMap->count()) {
            $nextLocation = $this->findNextLocation();
            $this->currentLocation = $nextLocation;
            $this->path->add($nextLocation);
        }

        $this->applyInitialLocation();
    }

    /**
     * @return CollectionInterface<string>
     */
    public function getPath(): CollectionInterface
    {
        return clone $this->path;
    }

    public function getLength(): float
    {
        $length = 0.0;

        for ($i = 0; $i < $this->path->count() - 1; ++$i) {
            $length += $this->distanceMap->getDistance($this->path[$i], $this->path[$i + 1])->getValue();
        }

        return $length;
    }

    private function findNextLocation(): string
    {
        $probabilities = new TypedMap('string', 'float');
        $total = 0.0;

        foreach ($this->getUnvisitedLocations() as $locationId) {
            $pheromone = $this->pheromoneMatrix->getPheromone($this->currentLocation, $locationId);
            $invertedDistance = 1 / ($this->distanceMap->getDistance($this->currentLocation, $locationId)->getValue()
                    / $this->distanceCoefficient);

            $probability = pow($pheromone, $this->alpha) * pow($invertedDistance, $this->beta);
            $probabilities->put($locationId, $probability);
            $total += $probability;
        }

        if (!$probabilities->isEmpty()) {
            $randomValue = (new Randomizer())->getFloat(0, $total);
            $currentValue = 0.0;

            foreach ($probabilities as $locationId => $probability) {
                $currentValue += $probability;

                /* @phpstan-ignore-next-line */
                if ($currentValue >= $randomValue) {
                    return $locationId;
                }
            }
        }

        return $this->getFirstUnvisitedLocation();
    }

    private function applyInitialLocation(): void
    {
        $this->currentLocation = $this->initialLocation;
        $this->path->add($this->initialLocation);
    }

    private function getFirstUnvisitedLocation(): string
    {
        foreach ($this->distanceMap->keys() as $locationId) {
            if (!$this->path->contains($locationId)) {
                return $locationId;
            }
        }

        throw new UnexpectedValueException('No matching location found!');
    }

    /**
     * @return string[]
     */
    private function getUnvisitedLocations(): array
    {
        return array_values(array_filter(
            $this->distanceMap->keys(),
            fn (string $locationId) => !$this->path->contains($locationId)
        ));
    }
}
