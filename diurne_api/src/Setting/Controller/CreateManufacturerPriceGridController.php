<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\ManufacturerPriceGrid\CreateManufacturerPriceGridCommand;
use App\Setting\DTO\CreateManufacturerPriceGridRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer-price-grids', name: 'manufacturer_price_grid_create', methods: ['POST'])]
class CreateManufacturerPriceGridController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Manufacturer price grid created successfully',
        content: new OA\JsonContent(ref: new Model(type: SuccessResponse::class))
    )]
    #[OA\RequestBody(
        description: 'Manufacturer price grid data',
        content: new OA\JsonContent(
            required: ['manufacturerId', 'qualityId', 'tarifGroupId'],
            properties: [
                new OA\Property(property: 'manufacturerId', type: 'integer'),
                new OA\Property(property: 'qualityId', type: 'integer'),
                new OA\Property(property: 'tarifGroupId', type: 'integer'),
                new OA\Property(property: 'tariffGrid', type: 'string'),
                new OA\Property(property: 'knots', type: 'integer'),
                new OA\Property(property: 'special', type: 'string'),
                new OA\Property(property: 'standardVelours', type: 'string'),
                new OA\Property(property: 'isActive', type: 'boolean'),
            ]
        )
    )]
    #[OA\Tag(name: 'Manufacturer Price Grids')]
    public function __invoke(
        #[MapRequestPayload] CreateManufacturerPriceGridRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new CreateManufacturerPriceGridCommand(
            $requestDto->manufacturerId,
            $requestDto->qualityId,
            $requestDto->tarifGroupId,
            $requestDto->tariffGrid,
            $requestDto->knots,
            $requestDto->special,
            $requestDto->standardVelours,
            $requestDto->isActive ?? true,
            $requestDto->prices ?? null
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'manufacturer_price_grid_create',
            $response->toArray(),
            'Manufacturer price grid created successfully'
        );
    }
}
