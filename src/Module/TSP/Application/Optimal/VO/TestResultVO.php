<?php

namespace App\Module\TSP\Application\Optimal\VO;

class TestResultVO
{
    public function __construct(
        public float $meanTime,
        public float $meanLength,
        public int $iterations,
        public int $testAttemptsNum,
        public TestParametersVO $parameters,
    ) {
    }
}
