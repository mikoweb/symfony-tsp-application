<?php

namespace App\Shared\Application\Interaction\SharedQuery;

use App\Core\Infrastructure\Interaction\SharedQuery\SharedQueryInterface;
use App\Shared\Domain\LocationCollection;

readonly class AskForDistanceMapQuery implements SharedQueryInterface
{
    public function __construct(
        public LocationCollection $locations,
    ) {
    }
}
