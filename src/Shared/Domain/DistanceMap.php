<?php

namespace App\Shared\Domain;

use PhpUnitConversion\Unit\Length\KiloMeter;
use Ramsey\Collection\Map\AbstractTypedMap;
use Ramsey\Collection\Map\TypedMap;

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
