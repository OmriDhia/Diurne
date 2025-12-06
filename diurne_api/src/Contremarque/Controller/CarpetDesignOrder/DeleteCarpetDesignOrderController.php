<?php

namespace App\Contremarque\Controller\CarpetDesignOrder;

use Exception;
use App\Contremarque\Bus\Command\DeleteCarpetDesignOrder\DeleteCarpetDesignOrderCommand;
use App\Common\Controller\CommandQueryController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class DeleteCarpetDesignOrderController extends CommandQueryController
{
    #[Route(
        path: '/api/carpet-design-orders/{id}',
        name: 'delete_carpet_design_order',
        methods: ['DELETE']
    )]
    #[OA\Response(
        response: 204,
        description: 'Carpet Design Order deleted successfully'
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
                new OA\Property(property: 'message', type: 'string', example: 'Insufficient permissions to delete a carpet design order')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Carpet Design Order not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 404),
                new OA\Property(property: 'message', type: 'string', example: 'Carpet Design Order with ID 1 not found')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the Carpet Design Order to delete',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        // Check authorization
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse([
                'code' => 403,
                'message' => 'Insufficient permissions to delete a carpet design order',
            ], Response::HTTP_FORBIDDEN);
        }
        $session = $this->container->get('request_stack')->getSession(); // Get the session service
        $session->start(); // Start the session if not already started

        $user = $session->get('user');
        try {
            $command = new DeleteCarpetDesignOrderCommand($id, $user);
            $this->handle($command);

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
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
