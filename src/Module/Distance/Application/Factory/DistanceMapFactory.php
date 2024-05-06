<?php

namespace App\Module\Distance\Application\Factory;

use App\Module\Distance\Application\Math\GeoDistance;
use App\Shared\Domain\DistanceMap;
use App\Shared\Domain\Location;
use App\Shared\Domain\LocationCollection;
use PhpUnitConversion\Unit\Length\KiloMeter;
use Ramsey\Collection\Map\TypedMap;

readonly class DistanceMapFactory
{
    public function createMap(LocationCollection $locations): DistanceMap
    {
        $map = new DistanceMap();

        foreach ($locations as $a) {
            $distances = new TypedMap('string', KiloMeter::class);
            $this->putDistances($distances, $a, $locations);
            $map->put($a->id, $distances);
        }

        return $map;
    }

    /**
     * @param TypedMap<string, KiloMeter> $distances
     */
    private function putDistances(TypedMap $distances, Location $a, LocationCollection $locations): void
    {
        foreach ($locations as $b) {
            $distances->put(
                $b->id,
                $a->id === $b->id
                    ? new KiloMeter(0.0)
                    : GeoDistance::calcDistanceV2($a, $b)
            );
        }
    }
}
