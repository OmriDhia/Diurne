<?php

namespace App\Contremarque\Controller\ImageCommand;


use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CancelImageCommand\CancelImageCommandCommand;
use Exception;

use App\Common\Controller\CommandQueryController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CanceledImageCommandController extends CommandQueryController
{
    #[Route(
        path: '/api/cancelImageCommand/{id}',
        name: 'cancel_image_command',
        methods: ['PUT', 'PATCH']
    )]
    #[OA\Response(
        response: 204,
        description: 'image command canceled successfully'
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
                new OA\Property(property: 'message', type: 'string', example: 'Insufficient permissions to delete a image command')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'image command not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 404),
                new OA\Property(property: 'message', type: 'string', example: 'image command with ID 1 not found')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the image command to canceled',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        // Check authorization
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse([
                'code' => 403,
                'message' => 'Insufficient permissions to delete a image command',
            ], Response::HTTP_FORBIDDEN);
        }
        $session = $this->container->get('request_stack')->getSession(); // Get the session service
        $session->start(); // Start the session if not already started

        $user = $session->get('user');
        try {
            $command = new CancelImageCommandCommand($id, $user);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'cancel_image_command',
                $response->toArray(),
                'image command canceled successfully.'
            );
        } catch (NotFoundHttpException $e) {

            exit;
            return new JsonResponse([
                'code' => 404,
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {

            exit;
            return new JsonResponse([
                'code' => 500,
                'message' => 'Internal server error',
                'errors' => [$e->getMessage()],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
