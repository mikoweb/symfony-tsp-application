<?php

namespace App\Module\TSP\UI\Dto;

use App\Module\TSP\Domain\Constant;
use App\Shared\UI\Dto\LocationDto;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SolveRequestDto
{
    public function __construct(
        /**
         * @var LocationDto[]
         */
        #[Assert\NotBlank]
        public array $locations,
        public int $iterations = Constant::DEFAULT_ITERATIONS,
        public int $initialLocationIndex = 0,
        public float $alpha = Constant::DEFAULT_ALPHA,
        public float $beta = Constant::DEFAULT_BETA,
        public int $distanceCoefficient = Constant::DEFAULT_DISTANCE_COEFFICIENT,
        public float $evaporation = Constant::DEFAULT_EVAPORATION,
        public float $antFactor = Constant::DEFAULT_ANT_FACTOR,
        public float $c = Constant::DEFAULT_PHEROMONE_INITIAL_VALUE,
    ) {
    }
}
