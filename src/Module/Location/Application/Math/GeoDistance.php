<?php

namespace App\Module\Location\Application\Math;

use App\Module\Location\Domain\Constant;
use App\Shared\Domain\Location;
use PhpUnitConversion\Unit\Length\KiloMeter;

/**
 * @see https://pl.wikibooks.org/wiki/Astronomiczne_podstawy_geografii/Odleg%C5%82o%C5%9Bci
 * @see https://community.fabric.microsoft.com/t5/Desktop/How-to-calculate-lat-long-distance/td-p/1488227
 */
class GeoDistance
{
    public static function calcDistanceV1(Location $a, Location $b): KiloMeter
    {
        return new KiloMeter(sqrt(
            pow($b->lat - $a->lat, 2) +
            pow(cos(($a->lat * M_PI) / 180) * ($b->lng - $a->lng), 2)
        ) * Constant::GEO_DEG_KM);
    }

    public static function calcDistanceV2(Location $a, Location $b): KiloMeter
    {
        return new KiloMeter(acos(
            sin(deg2rad($a->lat)) *
            sin(deg2rad($b->lat)) +
            cos(deg2rad($a->lat)) *
            cos(deg2rad($b->lat)) *
            cos(deg2rad($b->lng) - deg2rad($a->lng))
        ) * Constant::EARTH_RADIUS_KM);
    }
}
