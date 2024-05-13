<?php

namespace App\Module\TSP\UI\Controller;

use App\Core\UI\Api\Controller\AbstractRestController;
use App\Module\TSP\Domain\Constant;
use App\Module\TSP\UI\Dto\DefaultParametersDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class DefaultParametersController extends AbstractRestController
{
    #[OA\Tag(name: 'TSP')]
    #[OA\Get]
    #[OA\Response(
        response: 200,
        description: 'Get default parameters',
        content: new OA\JsonContent(
            ref: new Model(type: DefaultParametersDto::class)
        )
    )]
    public function get(): Response
    {
        return $this->json(new DefaultParametersDto(
            iterations: Constant::DEFAULT_ITERATIONS,
            alpha: Constant::DEFAULT_ALPHA,
            beta: Constant::DEFAULT_BETA,
            distanceCoefficient: Constant::DEFAULT_DISTANCE_COEFFICIENT,
            evaporation: Constant::DEFAULT_EVAPORATION,
            antFactor: Constant::DEFAULT_ANT_FACTOR,
            c: Constant::DEFAULT_PHEROMONE_INITIAL_VALUE,
        ));
    }
}
