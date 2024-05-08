<?php

namespace App\Module\TSP\Application\Optimal\VO;

readonly class TestParametersVO
{
    public function __construct(
        public float $alpha,
        public float $beta,
        public int $distanceCoefficient,
        public float $evaporation,
        public float $antFactor,
        public float $c,
    ) {
    }
}
