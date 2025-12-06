<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\ManufacturerPriceGrid\UpdateManufacturerPriceGridCommand;
use App\Setting\DTO\UpdateManufacturerPriceGridRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer-price-grids/{id}', name: 'manufacturer_price_grid_update', methods: ['PUT'])]
class UpdateManufacturerPriceGridController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Manufacturer price grid updated successfully',
        content: new OA\JsonContent(ref: new Model(type: SuccessResponse::class))
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Price grid ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        description: 'Manufacturer price grid update data',
        content: new OA\JsonContent(
            properties: [
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
        int                                                        $id,
        #[MapRequestPayload] UpdateManufacturerPriceGridRequestDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new UpdateManufacturerPriceGridCommand(
            $id,
            $requestDto->manufacturerId ?? null,
            $requestDto->tarifGroupId,
            $requestDto->tariffGrid,
            $requestDto->knots,
            $requestDto->special,
            $requestDto->standardVelours,
            $requestDto->isActive,
            $requestDto->prices ?? null
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'manufacturer_price_grid_update',
            $response->toArray(),
            'Manufacturer price grid updated successfully'
        );
    }
}
