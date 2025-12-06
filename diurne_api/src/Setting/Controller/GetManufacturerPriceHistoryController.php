<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\ManufacturerPriceGrid\GetManufacturerPriceHistoryQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer-price-history', name: 'manufacturer_price_history_get', methods: ['GET'])]
class GetManufacturerPriceHistoryController extends CommandQueryController
{
    #[OA\Parameter(
        name: 'manufacturerId',
        in: 'query',
        description: 'Manufacturer ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'qualityId',
        in: 'query',
        description: 'Quality ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Number of history items to return',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Manufacturer price history retrieved successfully',
        content: new OA\JsonContent(ref: new Model(type: SuccessResponse::class))
    )]
    #[OA\Tag(name: 'Manufacturer Price Grids')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new GetManufacturerPriceHistoryQuery(
            $request->query->getInt('manufacturerId'),
            $request->query->getInt('qualityId'),
            $request->query->getInt('limit', 10)
        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'manufacturer_price_history_get',
            $response,
            'Manufacturer price history retrieved successfully'
        );
    }
}
