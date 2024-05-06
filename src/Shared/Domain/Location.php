<?php

namespace App\Shared\Domain;

use Symfony\Component\Uid\Uuid;

final readonly class Location
{
    public string $id;

    public function __construct(
        public float $lat,
        public float $lng,
        public string $name,
    ) {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
