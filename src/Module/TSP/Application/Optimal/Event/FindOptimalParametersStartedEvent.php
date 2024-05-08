<?php

namespace App\Module\TSP\Application\Optimal\Event;

use Symfony\Contracts\EventDispatcher\Event;

class FindOptimalParametersStartedEvent extends Event
{
    public function __construct(
        public readonly int $parametersToTestNumber,
    ) {
    }
}
