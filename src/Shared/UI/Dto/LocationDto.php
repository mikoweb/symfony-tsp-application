<?php

namespace App\Shared\UI\Dto;

use App\Shared\Domain\Location;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LocationDto
{
    public function __construct(
        public float $lat,
        public float $lng,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }

    public function toLocation(): Location
    {
        return new Location(
            lat: $this->lat,
            lng: $this->lng,
            name: $this->name,
        );
    }
}
