<?php

namespace App\Contremarque\Controller\Sample;

use Exception;
use App\Contremarque\Bus\Query\GetSampleById\GetSampleByIdQuery;
use App\Contremarque\DTO\GetSampleByIdRequestDto;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use App\Contremarque\Bus\Command\Sample\SampleResponse;

class GetSampleByIdController extends CommandQueryController
{
    #[Route(
        path: '/api/samples/{id}',
        name: 'get_sample_by_id',
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'Sample retrieved successfully',
        content: new OA\JsonContent(ref: '#/components/schemas/SampleResponse')
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
                new OA\Property(property: 'message', type: 'string', example: 'Insufficient permissions to view a sample')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Sample not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 404),
                new OA\Property(property: 'message', type: 'string', example: 'Sample with ID 1 not found')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the Sample',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapQueryString] GetSampleByIdRequestDto $dto
    ): JsonResponse {
        // Check authorization
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 403,
                'message' => 'Insufficient permissions to view a sample',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $query = new GetSampleByIdQuery($id);
            $response = $this->ask($query);

            return SuccessResponse::create(
                'sample_by_id',
                $response->toArray(),
                'Sample retrieved successfully'
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
