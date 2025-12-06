<?php

namespace App\Contremarque\Controller\Sample;

use Exception;
use App\Contremarque\Bus\Query\GetAllSamplesByCarpetDesignOrder\GetAllSamplesByCarpetDesignOrderQuery;
use App\Contremarque\DTO\GetAllSamplesByCarpetDesignOrderRequestDto;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Contremarque\Bus\Command\Sample\SampleResponse;

class GetAllSamplesByCarpetDesignOrderController extends CommandQueryController
{
    #[Route(
        path: '/api/samples/carpet-design-order/{carpetDesignOrderId}',
        name: 'get_all_samples_by_carpet_design_order',
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'List of samples for the given Carpet Design Order',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'count', type: 'integer', example: 10),
                new OA\Property(property: 'page', type: 'integer', example: 1),
                new OA\Property(property: 'itemsPerPage', type: 'integer', example: 10),
                new OA\Property(
                    property: 'samples',
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/SampleResponse')
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - Authentication required',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 401),
                new OA\Property(property: 'message', type: 'string', example: 'Unauthorized to access this content')
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Forbidden - Insufficient permissions',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 403),
                new OA\Property(property: 'message', type: 'string', example: 'Insufficient permissions to view samples')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'No samples found for the given Carpet Design Order',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 404),
                new OA\Property(property: 'message', type: 'string', example: 'No samples found for Carpet Design Order ID 1')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'carpetDesignOrderId',
        in: 'path',
        description: 'ID of the Carpet Design Order',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Page number',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    #[OA\Parameter(
        name: 'itemsPerPage',
        in: 'query',
        description: 'Items per page',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 10)
    )]
    #[OA\Parameter(
        name: 'orderBy',
        in: 'query',
        description: 'Order by field',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['id', 'diCommandNumber', 'createdAt', 'updatedAt'], default: 'id')
    )]
    #[OA\Parameter(
        name: 'orderWay',
        in: 'query',
        description: 'Order way (ASC or DESC)',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['ASC', 'DESC'], default: 'DESC')
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $carpetDesignOrderId,
        #[MapQueryString] GetAllSamplesByCarpetDesignOrderRequestDto $dto
    ): JsonResponse {
        // Check authorization
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 403,
                'message' => 'Insufficient permissions to view samples',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $query = new GetAllSamplesByCarpetDesignOrderQuery(
                $dto->getPage(),
                $dto->getItemsPerPage(),
                $dto->getOrderBy(),
                $dto->getOrderWay(),
                $carpetDesignOrderId
            );

            $response = $this->ask($query);

            return SuccessResponse::create(
                'samples_by_carpet_design_order',
                $response->toArray(),
                'Samples retrieved successfully'
            );
        } catch (NotFoundHttpException $e) {
            return new JsonResponse([
                'code' => 404,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return new JsonResponse([
                'code' => 500,
                'message' => 'Internal server error',
                'errors' => [$e->getMessage()],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
