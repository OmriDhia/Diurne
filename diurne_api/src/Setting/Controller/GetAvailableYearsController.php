<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\ManufacturerPriceGrid\GetAvailableYearsQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/manufacturer-price-grids/tarif-groups', name: 'manufacturer_price_grids_get_years', methods: ['GET'])]
class GetAvailableYearsController extends CommandQueryController
{
    #[OA\Parameter(
        name: 'manufacturerId',
        in: 'query',
        description: 'Filter tarif groups by manufacturer ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Available tarif groups retrieved successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'year', type: 'string'),
                        ],
                        type: 'object'
                    )
                )
            ]
        )
    )]
    #[OA\Tag(name: 'Manufacturer Price Grids')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $manufacturerId = $request->query->getInt('manufacturerId') ?: null;

        $query = new GetAvailableYearsQuery($manufacturerId);
        $years = $this->ask($query);

        return SuccessResponse::create(
            'manufacturer_price_grids_get_years',
            $years->toArray(),
            'Available tarif groups retrieved successfully'
        );
    }
}
