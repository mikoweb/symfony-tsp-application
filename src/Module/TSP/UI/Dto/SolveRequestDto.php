<?php

namespace App\Module\TSP\UI\Dto;

use App\Module\TSP\Application\Validator\SolveRequestConstraint;
use App\Module\TSP\Domain\Constant;
use App\Shared\UI\Dto\LocationDto;
use Symfony\Component\Validator\Constraints as Assert;

#[SolveRequestConstraint]
readonly class SolveRequestDto
{
    public function __construct(
        /**
         * @var LocationDto[]
         */
        #[Assert\NotBlank]
        public array $locations,

        #[Assert\Range(min: 1, max: 1000)]
        public int $iterations = Constant::DEFAULT_ITERATIONS,

        #[Assert\Range(min: 0)]
        public int $initialLocationIndex = 0,

        #[Assert\GreaterThan(value: 0)]
        #[Assert\LessThanOrEqual(value: 10)]
        public float $alpha = Constant::DEFAULT_ALPHA,

        #[Assert\GreaterThan(value: 0)]
        #[Assert\LessThanOrEqual(value: 10)]
        public float $beta = Constant::DEFAULT_BETA,

        #[Assert\Range(min: 1, max: 10000)]
        public int $distanceCoefficient = Constant::DEFAULT_DISTANCE_COEFFICIENT,

        #[Assert\GreaterThanOrEqual(value: 0)]
        #[Assert\LessThan(value: 1)]
        public float $evaporation = Constant::DEFAULT_EVAPORATION,

        #[Assert\GreaterThan(value: 0)]
        #[Assert\LessThanOrEqual(value: 10)]
        public float $antFactor = Constant::DEFAULT_ANT_FACTOR,

        #[Assert\GreaterThan(value: 0)]
        #[Assert\LessThanOrEqual(value: 1)]
        public float $c = Constant::DEFAULT_PHEROMONE_INITIAL_VALUE,
    ) {
    }
}
