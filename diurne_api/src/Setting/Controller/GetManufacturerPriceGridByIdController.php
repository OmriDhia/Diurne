<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\ManufacturerPriceGrid\GetManufacturerPriceGridByIdQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer-price-grids/{id}', name: 'manufacturer_price_grid_get_by_id', methods: ['GET'])]
class GetManufacturerPriceGridByIdController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Manufacturer price grid retrieved successfully',
        content: new OA\JsonContent(ref: new Model(type: SuccessResponse::class))
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'Price grid ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Manufacturer Price Grids')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetManufacturerPriceGridByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'manufacturer_price_grid_get_by_id',
            $response->toArray(),
            'Manufacturer price grid retrieved successfully'
        );
    }
}
