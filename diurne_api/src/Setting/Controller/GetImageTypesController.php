<?php

declare(strict_types=1);

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Query\ImageType\GetImageTypesQuery;
use App\Setting\Entity\ImageType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/image-types', name: 'get_image_types', methods: ['GET'])]
class GetImageTypesController extends CommandQueryController
{
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
        description: 'Fetch all available image types with pagination',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: ImageType::class))
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
    #[OA\Tag(name: 'Setting')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $page = $request->query->getInt('page');
        $itemsPerPage = $request->query->getInt('itemsPerPage');
        $forceRefresh = $request->query->getBoolean('forceRefresh', false);

        $query = new GetImageTypesQuery(
            $page > 0 ? $page : null,
            $itemsPerPage > 0 ? $itemsPerPage : null,
            $forceRefresh
        );
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_image_types',
            $response->toArray(),
            'Image types fetched successfully'
        );
    }
}
