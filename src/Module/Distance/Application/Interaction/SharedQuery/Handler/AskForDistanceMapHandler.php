<?php

namespace App\Module\Distance\Application\Interaction\SharedQuery\Handler;

use App\Module\Distance\Application\Factory\DistanceMapFactory;
use App\Shared\Application\Interaction\SharedQuery\AskForDistanceMapQuery;
use App\Shared\Domain\DistanceMap;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

readonly class AskForDistanceMapHandler
{
    public function __construct(
        private DistanceMapFactory $distanceMapFactory,
    ) {
    }

    #[AsMessageHandler(bus: 'shared_query_bus')]
    public function handle(AskForDistanceMapQuery $query): DistanceMap
    {
        return $this->distanceMapFactory->createMap($query->locations);
    }
}
