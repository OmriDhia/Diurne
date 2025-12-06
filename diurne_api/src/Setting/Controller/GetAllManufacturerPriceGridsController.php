<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\ManufacturerPriceGrid\GetAllManufacturerPriceGridsQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer-price-grids', name: 'manufacturer_price_grids_get_all', methods: ['GET'])]
class GetAllManufacturerPriceGridsController extends CommandQueryController
{
    #[OA\Parameter(
        name: 'manufacturerId',
        in: 'query',
        description: 'Filter by manufacturer ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'year',
        in: 'query',
        description: 'Filter by year',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'qualityId',
        in: 'query',
        description: 'Filter by quality ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Page number',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'itemsPerPage',
        in: 'query',
        description: 'Items per page',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'onlyActive',
        in: 'query',
        description: 'Show only active price grids',
        required: false,
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Response(
        response: 200,
        description: 'Manufacturer price grids retrieved successfully',
        content: new OA\JsonContent(ref: new Model(type: SuccessResponse::class))
    )]
    #[OA\Tag(name: 'Manufacturer Price Grids')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetAllManufacturerPriceGridsQuery(
            $request->query->getInt('manufacturerId') ?: null,
            $request->query->getInt('tarifGroupId') ?: null,
            $request->query->getInt('qualityId') ?: null,
            $request->query->getInt('page') ?: null,
            $request->query->getInt('itemsPerPage') ?: null,
            $request->query->getBoolean('onlyActive', true)
        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'manufacturer_price_grids_get_all',
            $response->toArray(),
            'Manufacturer price grids retrieved successfully'
        );
    }
}
