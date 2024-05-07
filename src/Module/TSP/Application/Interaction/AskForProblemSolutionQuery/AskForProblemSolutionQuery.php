<?php

namespace App\Module\TSP\Application\Interaction\AskForProblemSolutionQuery;

use App\Core\Infrastructure\Interaction\Query\QueryInterface;
use App\Module\TSP\UI\Dto\SolveRequestDto;

readonly class AskForProblemSolutionQuery implements QueryInterface
{
    public function __construct(
        public SolveRequestDto $solveRequest,
    ) {
    }
}
