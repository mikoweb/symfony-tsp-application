<?php

namespace App\Module\TSP\Domain;

use Ramsey\Collection\CollectionInterface;

readonly class ResultItem
{
    public function __construct(
        /**
         * @var CollectionInterface<string>
         */
        public CollectionInterface $path,
        public float $length,
    ) {
    }
}
