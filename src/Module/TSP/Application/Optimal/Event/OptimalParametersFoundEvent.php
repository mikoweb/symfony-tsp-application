<?php

namespace App\Module\TSP\Application\Optimal\Event;

use App\Module\TSP\Application\Optimal\VO\TestResultVO;
use Ramsey\Collection\CollectionInterface;
use Symfony\Contracts\EventDispatcher\Event;

class OptimalParametersFoundEvent extends Event
{
    public function __construct(
        /**
         * @var CollectionInterface<TestResultVO>
         */
        public readonly CollectionInterface $results,
    ) {
    }
}
