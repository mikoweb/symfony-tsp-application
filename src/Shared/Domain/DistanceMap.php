<?php

namespace App\Shared\Domain;

use PhpUnitConversion\Unit\Length\KiloMeter;
use Ramsey\Collection\Map\AbstractTypedMap;
use Ramsey\Collection\Map\TypedMap;
use UnexpectedValueException;

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

    public function getDistance(string $a, string $b): KiloMeter
    {
        $this->checkDistanceExists($a, $b);

        return $this->get($a)->get($b);
    }

    private function checkDistanceExists(string $a, string $b): void
    {
        if (!$this->containsKey($a)) {
            throw new UnexpectedValueException(sprintf('Not found distance row %s', $a));
        }

        if (!$this->get($a)->containsKey($b)) {
            throw new UnexpectedValueException(sprintf('Not found distance cell %s : %s', $a, $b));
        }
    }
}
