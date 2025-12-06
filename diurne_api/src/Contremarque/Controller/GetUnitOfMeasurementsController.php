<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetUnitOfMeasurements\GetUnitOfMeasurementsQuery;
use App\Contremarque\Bus\Query\GetUnitOfMeasurements\GetUnitOfMeasurementsResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetUnitOfMeasurementsController extends CommandQueryController
{
    #[Route('/api/unitOfMeasurements', name: 'get_unit_of_measurements', methods: ['GET'])]
    #[OA\Parameter(
        name: "feetInchCombinated",
        in: "query",
        description: "Whether to combine feet and inch (1 for combined, 0 for separate)",
        required: true,
        schema: new OA\Schema(type: "string", enum: ["0", "1"])
    )]
    #[OA\Parameter(
        name: "page",
        in: "query",
        description: "Page number (omit to fetch all records)",
        required: false,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        name: "itemsPerPage",
        in: "query",
        description: "Number of items per page (omit to fetch all records)",
        required: false,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        name: "forceRefresh",
        in: "query",
        description: "Force refresh the cache",
        required: false,
        schema: new OA\Schema(type: "boolean")
    )]
    #[OA\Response(
        response: 200,
        description: 'Get unit of measurements with pagination',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'abbreviation', type: 'string'),
                        ]
                    )
                ),
                new OA\Property(
                    property: 'meta',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'total_items', type: 'integer'),
                        new OA\Property(property: 'page', type: 'integer'),
                        new OA\Property(property: 'items_per_page', type: 'integer'),
                    ]
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $feetInchCombinated = $request->query->get('feetInchCombinated');
        if (!in_array($feetInchCombinated, ['0', '1'], true)) {
            return new JsonResponse([
                'code' => 400,
                'message' => 'feetInchCombinated must be "0" or "1"',
            ], 400);
        }

        $page = $request->query->getInt('page');
        $itemsPerPage = $request->query->getInt('itemsPerPage');
        $forceRefresh = $request->query->getBoolean('forceRefresh', false);

        $getUnitOfMeasurementsQuery = new GetUnitOfMeasurementsQuery(
            (string) $feetInchCombinated,
            $page > 0 ? $page : null,
            $itemsPerPage > 0 ? $itemsPerPage : null,
            $forceRefresh
        );

        /** @var GetUnitOfMeasurementsResponse $response */
        $response = $this->ask($getUnitOfMeasurementsQuery);

        return SuccessResponse::create(
            'get_unit_of_measurements',
            $response->toArray(),
            'Unit of measurements fetched successfully'
        );
    }
}
