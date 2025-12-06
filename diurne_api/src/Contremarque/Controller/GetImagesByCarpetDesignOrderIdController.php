<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Bus\Query\QueryResponse;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetImagesByCarpetDesignOrderId\GetImagesByCarpetDesignOrderIdQuery;
use App\Contremarque\Entity\Image;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetImagesByCarpetDesignOrderIdController extends CommandQueryController
{
    #[Route('/api/carpet-design-order/{carpetDesignOrderId}/images', name: 'get_images_by_carpet_design_order_id', methods: ['GET'])]
    #[OA\Parameter(
        name: "carpetDesignOrderId",
        in: "path",
        description: "The ID of the Carpet Design Order",
        required: true,
        schema: new OA\Schema(type: "integer")
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
        description: 'Images retrieval by Carpet Design Order ID with pagination',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: Image::class))
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
    public function __invoke(int $carpetDesignOrderId, Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $page = $request->query->getInt('page');
        $itemsPerPage = $request->query->getInt('itemsPerPage');
        $forceRefresh = $request->query->getBoolean('forceRefresh', false);

        $query = new GetImagesByCarpetDesignOrderIdQuery(
            $carpetDesignOrderId,
            $page > 0 ? $page : null,
            $itemsPerPage > 0 ? $itemsPerPage : null,
            $forceRefresh
        );

        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_images_by_carpet_design_order_id',
            $response->toArray(),
            'Images fetched successfully'
        );
    }
}
