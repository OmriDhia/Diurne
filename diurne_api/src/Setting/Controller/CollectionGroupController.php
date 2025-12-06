<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\CollectionGroup\CollectionGroupQueryResponse;
use App\Setting\Bus\Query\CollectionGroup\GetAllCollectionGroupsQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/collection-groups', name: 'collection_group_get_all', methods: ['GET'])]
class CollectionGroupController extends CommandQueryController
{
    private const HTTP_STATUS_UNAUTHORIZED = 401;
    private const DEFAULT_PAGE = 0;
    private const DEFAULT_ITEMS_PER_PAGE = 0;

    #[OA\Parameter(
        name: "page",
        in: "query",
        description: "Page number",
        required: false,
        schema: new OA\Schema(type: "integer", default: self::DEFAULT_PAGE)
    )] 
    #[OA\Parameter(
        name: "itemsPerPage",
        in: "query",
        description: "Number of items per page",
        required: false,
        schema: new OA\Schema(type: "integer", default: self::DEFAULT_ITEMS_PER_PAGE)
    )]
    #[OA\Response(
        response: 200,
        description: 'Fetch all collection groups',
        content: new Model(type: CollectionGroupQueryResponse::class)
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

        $page = (int) $request->query->get('page', self::DEFAULT_PAGE);
        $itemsPerPage = (int) $request->query->get('itemsPerPage', self::DEFAULT_ITEMS_PER_PAGE);


        $query = new GetAllCollectionGroupsQuery($page, $itemsPerPage);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'collection_groups_retrieval',
            $response->toArray()
        );
    }
}
