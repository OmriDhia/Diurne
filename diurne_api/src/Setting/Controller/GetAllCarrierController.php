<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\Carrier\GetAllCarrierQuery;
use App\Setting\Entity\Carrier;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling requests related to fetching all carriers.
 */
#[Route('/api/carrier', name: 'get_all_carriers', methods: ['GET'])]
class GetAllCarrierController extends CommandQueryController
{
    private const HTTP_STATUS_UNAUTHORIZED = 401;
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_ITEMS_PER_PAGE = 0;

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
        description: 'Fetch all available carriers with pagination',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Carrier::class))
                ),
                new OA\Property(
                    property: 'pagination',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'totalItems', type: 'integer'),
                        new OA\Property(property: 'totalPages', type: 'integer'),
                        new OA\Property(property: 'currentPage', type: 'integer'),
                        new OA\Property(property: 'itemsPerPage', type: 'integer'),
                    ]
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(
                ['code' => self::HTTP_STATUS_UNAUTHORIZED, 'message' => 'Unauthorized to access this content'],
                self::HTTP_STATUS_UNAUTHORIZED
            );
        }

        $page = $request->query->getInt('page');
        $itemsPerPage = $request->query->getInt('itemsPerPage');
        $forceRefresh = $request->query->getBoolean('forceRefresh', false);

        $query = new GetAllCarrierQuery(
            $page > 0 ? $page : null,
            $itemsPerPage > 0 ? $itemsPerPage : null,
            $forceRefresh
        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_all_carriers',
            $response->toArray(),
            'Carriers fetched successfully'
        );
    }
}