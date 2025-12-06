<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetDesignOrderById\GetCarpetDesignOrderByIdQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/carpet-design-orders/{id}', name: 'carpet_design_order_get_by_id', methods: ['GET'])]
class GetCarpetDesignOrderByIdController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Fetch a specific carpet design order by its ID',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'projectDi', type: 'integer', nullable: true),
                new OA\Property(property: 'location', type: 'object', nullable: true),
                new OA\Property(property: 'status', type: 'object', nullable: true),
                new OA\Property(property: 'designers', type: 'array', items: new OA\Items(type: 'object')),
                new OA\Property(property: 'carpetSpecification', type: 'object', nullable: true),
                new OA\Property(property: 'customerInstruction', type: 'object', nullable: true),
                new OA\Property(property: 'variation', type: 'string', nullable: true),
                new OA\Property(property: 'variationImageReference', type: 'string', nullable: true),
                new OA\Property(property: 'transmition_date', type: 'string', format: 'date-time', nullable: true),
                new OA\Property(property: 'hasImageCommand', type: 'boolean'),
                new OA\Property(property: 'imageCommandIsCanceled', type: 'boolean'),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized access',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 401),
                new OA\Property(property: 'message', type: 'string', example: 'Unauthorized to access this content'),
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Carpet design order not found',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 404),
                new OA\Property(property: 'message', type: 'string', example: 'Resource not found'),
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'The ID of the carpet design order to fetch',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'forceRefresh',
        in: 'query',
        required: false,
        description: 'Force refresh the cache for this request',
        schema: new OA\Schema(type: 'boolean', default: false)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $forceRefresh = $request->query->getBoolean('forceRefresh', false);
            $query = new GetCarpetDesignOrderByIdQuery($id, $forceRefresh);
            $response = $this->ask($query);

            return SuccessResponse::create(
                'carpet_design_order_retrieval',
                $response->toArray(),
                'Carpet design order fetched successfully',
                Response::HTTP_OK
            );
        } catch (ResourceNotFoundException) {
            return new JsonResponse(['code' => 404, 'message' => 'Carpet design order not found'], Response::HTTP_NOT_FOUND);
        } catch (Exception) {
            return new JsonResponse(['code' => 500, 'message' => 'An unexpected error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
