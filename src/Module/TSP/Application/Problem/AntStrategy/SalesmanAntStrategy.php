<?php

namespace App\Module\TSP\Application\Problem\AntStrategy;

use App\Module\TSP\Application\Problem\AntInterface;
use App\Module\TSP\Application\Problem\AntStrategyInterface;
use App\Module\TSP\Application\Problem\PheromoneMatrix;
use App\Module\TSP\Application\Problem\SalesmanAnt;
use App\Module\TSP\Domain\Constant;
use App\Shared\Domain\DistanceMap;

class SalesmanAntStrategy implements AntStrategyInterface
{
    public function createAnt(
        string $initialLocation,
        DistanceMap $distanceMap,
        PheromoneMatrix $pheromoneMatrix,
        float $alpha = Constant::DEFAULT_ALPHA,
        float $beta = Constant::DEFAULT_BETA,
        int $distanceCoefficient = Constant::DEFAULT_DISTANCE_COEFFICIENT
    ): AntInterface {
        return new SalesmanAnt(
            initialLocation: $initialLocation,
            distanceMap: $distanceMap,
            pheromoneMatrix: $pheromoneMatrix,
            alpha: $alpha,
            beta: $beta,
            distanceCoefficient: $distanceCoefficient,
        );
    }
}
