<?php

namespace App\Module\Location\Domain;

final readonly class Location
{
    public function __construct(
        public float $lat,
        public float $lng,
        public string $name,
    ) {
    }
}
