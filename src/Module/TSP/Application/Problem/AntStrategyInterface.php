<?php

namespace App\Module\TSP\Application\Problem;

use App\Module\TSP\Domain\Constant;
use App\Shared\Domain\DistanceMap;

interface AntStrategyInterface
{
    public function createAnt(
        string $initialLocation,
        DistanceMap $distanceMap,
        PheromoneMatrix $pheromoneMatrix,
        float $alpha = Constant::DEFAULT_ALPHA,
        float $beta = Constant::DEFAULT_BETA,
        int $distanceCoefficient = Constant::DEFAULT_DISTANCE_COEFFICIENT,
    ): AntInterface;
}
