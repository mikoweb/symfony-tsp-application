<?php

namespace App\Module\TSP\Application\Problem;

use Ramsey\Collection\Collection;

interface AntInterface
{
    public function start(): void;

    /**
     * @return Collection<string>
     */
    public function getPath(): Collection;
}
