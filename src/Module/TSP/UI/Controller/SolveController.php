<?php

namespace App\Module\TSP\UI\Controller;

use App\Core\Infrastructure\Bus\QueryBusInterface;
use App\Core\UI\Api\Controller\AbstractRestController;
use App\Module\TSP\Application\Interaction\AskForProblemSolutionQuery\AskForProblemSolutionQuery;
use App\Module\TSP\UI\Dto\SolutionResultDto;
use App\Module\TSP\UI\Dto\SolveRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class SolveController extends AbstractRestController
{
    #[OA\Tag(name: 'TSP')]
    #[OA\Post(requestBody: new OA\RequestBody(attachables: [new Model(type: SolveRequestDto::class)]))]
    #[OA\Response(
        response: 200,
        description: 'Solve TSP Problem',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: SolutionResultDto::class)),
        )
    )]
    public function solve(
        #[MapRequestPayload] SolveRequestDto $dto,
        QueryBusInterface $queryBus,
    ): Response {
        return $this->json($queryBus->dispatch(new AskForProblemSolutionQuery($dto)));
    }
}
