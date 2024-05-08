<?php

namespace App\Module\TSP\Application\Optimal\Event;

use App\Module\TSP\Application\Optimal\VO\TestParametersVO;
use Symfony\Contracts\EventDispatcher\Event;

class ParametersTestedEvent extends Event
{
    public function __construct(
        public readonly TestParametersVO $parameters,
    ) {
    }
}
