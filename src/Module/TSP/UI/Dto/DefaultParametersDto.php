<?php

namespace App\Module\TSP\UI\Dto;

readonly class DefaultParametersDto
{
    public function __construct(
        public int $iterations,
        public float $alpha,
        public float $beta,
        public int $distanceCoefficient,
        public float $evaporation,
        public float $antFactor,
        public float $c,
    ) {
    }
}
