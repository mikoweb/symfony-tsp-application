<?php

namespace App\Module\TSP\Application\Problem;

use Ramsey\Collection\CollectionInterface;

interface AntInterface
{
    public function start(): void;

    /**
     * @return CollectionInterface<string>
     */
    public function getPath(): CollectionInterface;
    public function getLength(): float;
}
