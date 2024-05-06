<?php

namespace App\Module\Location\Domain;

use Ramsey\Collection\Map\AbstractTypedMap;
use Ramsey\Collection\Map\TypedMap;
use PhpUnitConversion\Unit\Length\KiloMeter;

/**
 * @extends AbstractTypedMap<string, TypedMap<string, KiloMeter>>
 */
class DistanceMap extends AbstractTypedMap
{
    public function getKeyType(): string
    {
        return 'string';
    }

    public function getValueType(): string
    {
        return TypedMap::class;
    }
}
