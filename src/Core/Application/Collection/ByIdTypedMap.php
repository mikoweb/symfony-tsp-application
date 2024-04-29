<?php

namespace App\Core\Application\Collection;

use Ramsey\Collection\Map\TypedMap;

/**
 * @extends TypedMap<string|int, object>
 */
class ByIdTypedMap extends TypedMap
{
    /**
     * @param object[] $data
     */
    public function __construct(string $keyType, string $valueType, array $data = [])
    {
        parent::__construct($keyType, $valueType);

        foreach ($data as $object) {
            $this->putObject($object);
        }
    }

    public function putObject(object $object): mixed
    {
        return $this->put($this->getId($object), $object);
    }

    protected function getId(object $object): string|int
    {
        $id = method_exists($object, 'getId') ? $object->getId() : $object->id;

        return match (gettype($id)) {
            'string', 'integer' => $id,
            default => (string) $id,
        };
    }
}
