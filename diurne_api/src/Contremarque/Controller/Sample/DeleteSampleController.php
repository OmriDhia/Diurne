<?php

namespace App\Contremarque\Controller\Sample;

use Exception;
use App\Contremarque\Bus\Command\Sample\DeleteSampleCommand;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class DeleteSampleController extends CommandQueryController
{
    #[Route(
        path: '/api/samples/{id}',
        name: 'delete_sample',
        methods: ['DELETE']
    )]
    #[OA\Response(
        response: 204,
        description: 'Sample deleted successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 204),
                new OA\Property(property: 'message', type: 'string', example: 'Sample deleted successfully')
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
                new OA\Property(property: 'message', type: 'string', example: 'Insufficient permissions to delete a sample')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Sample not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 404),
                new OA\Property(property: 'message', type: 'string', example: 'Sample not found')
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        // Check authorization
        if (!$this->isGranted('delete', 'contremarque')) {
            throw new AccessDeniedHttpException('Insufficient permissions to delete a sample');
        }

        try {
            // Map the ID to the command
            $command = new DeleteSampleCommand($id);

            // Handle the command
            $this->handle($command);

            // Return a 204 No Content response using SuccessResponse
            return SuccessResponse::create(
                'sample_deleted',
                [],
                'Sample deleted successfully'
            );
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                data: [
                    'code' => 404,
                    'message' => $e->getMessage(),
                ],
                status: Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return new JsonResponse(
                data: [
                    'code' => 500,
                    'message' => 'Internal server error',
                    'errors' => [$e->getMessage()],
                ],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}