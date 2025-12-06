<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\CarpetCollection\GetAllCarpetCollectionsQuery;
use App\Setting\Entity\CarpetCollection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/collections', name: 'collections_list', methods: ['GET'])]
class GetAllCarpetCollectionsController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch all carpet collections with pagination',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: CarpetCollection::class))
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
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'The page number',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'itemsPerPage',
        in: 'query',
        description: 'The number of items per page',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'forceRefresh',
        in: 'query',
        description: 'Force refresh the cache',
        schema: new OA\Schema(type: 'boolean')
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $page = $request->query->getInt('page');
        $itemsPerPage = $request->query->getInt('itemsPerPage');
        $forceRefresh = $request->query->getBoolean('forceRefresh', false);

        $query = new GetAllCarpetCollectionsQuery(
            $page > 0 ? $page : null,
            $itemsPerPage > 0 ? $itemsPerPage : null,
            $forceRefresh
        );
        $response = $this->ask($query);

        return SuccessResponse::create(
            'carpet_collections_retrieval',
            $response->toArray(),
            'Carpet collections fetched successfully'
        );
    }
}
