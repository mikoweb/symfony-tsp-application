<?php

namespace App\Shared\Domain;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Location>
 *
 * @method Location           first()
 * @method LocationCollection filter(callable $callback)
 * @method LocationCollection where(?string $propertyOrMethod, mixed $value)
 */
class LocationCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Location::class;
    }

    public function findOne(string $name, mixed $value): ?Location
    {
        $location = $this->where($name, $value);

        return $location->isEmpty() ? null : $location->first();
    }

    public function findById(string $id): ?Location
    {
        return $this->findOne('id', $id);
    }

    public function findByName(string $name): ?Location
    {
        return $this->findOne('name', $name);
    }
}
