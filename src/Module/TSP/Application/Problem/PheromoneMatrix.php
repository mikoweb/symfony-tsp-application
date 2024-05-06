<?php

namespace App\Module\TSP\Application\Problem;

use Ramsey\Collection\Map\AbstractTypedMap;
use Ramsey\Collection\Map\TypedMap;
use UnexpectedValueException;

/**
 * @extends AbstractTypedMap<string, TypedMap<string, float>>
 */
class PheromoneMatrix extends AbstractTypedMap
{
    /**
     * @param string[] $locations
     */
    public static function createFromLocations(array $locations, float $initialValue = 1.0): self
    {
        $matrix = new self();

        foreach ($locations as $a) {
            $cols = new TypedMap('string', 'float');
            $matrix->put($a, $cols);

            foreach ($locations as $b) {
                $cols->put($b, $initialValue);
            }
        }

        return $matrix;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getValueType(): string
    {
        return TypedMap::class;
    }

    public function getPheromone(string $a, string $b): float
    {
        $this->checkPheromoneExists($a, $b);

        return $this->get($a)->get($b);
    }

    public function updatePheromone(string $a, string $b, float $value): void
    {
        $this->checkPheromoneExists($a, $b);

        $this->get($a)->put($b, $value);
    }

    private function checkPheromoneExists(string $a, string $b): void
    {
        if (!$this->containsKey($a)) {
            throw new UnexpectedValueException(sprintf('Not found matrix row %s', $a));
        }

        if (!$this->get($a)->containsKey($b)) {
            throw new UnexpectedValueException(sprintf('Not found matrix cell %s : %s', $a, $b));
        }
    }
}
