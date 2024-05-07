<?php

namespace App\Module\TSP\UI\Dto;

use App\Shared\Domain\DistanceMap;
use App\Shared\Domain\Location;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use PhpUnitConversion\Unit\Length\KiloMeter;

readonly class SolutionResultDto
{
    public function __construct(
        /**
         * @var Location[]
         */
        public array $path,
        public KiloMeter $length,

        #[OA\Property(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(
                        property: '*',
                        properties: [
                            new OA\Property(property: '*', ref: new Model(type: KiloMeter::class)),
                        ],
                        type: 'object',
                    ),
                ],
                type: 'object',
            ),
        )]
        public DistanceMap $distanceMap,
    ) {
    }
}
