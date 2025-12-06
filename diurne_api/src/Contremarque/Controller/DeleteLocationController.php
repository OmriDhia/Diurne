<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Command\Location\DeleteLocationCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteLocationController extends CommandQueryController
{
    #[Route('/api/locations/{id}', name: 'location_deletion', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Location successfully deleted'
    )]
    #[OA\Response(
        response: 400,
        description: 'Cannot delete location due to existing associations'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Location ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $deleteLocationCommand = new DeleteLocationCommand($id);
        $this->handle($deleteLocationCommand);

        return new JsonResponse(['message' => 'Location deleted successfully'], 200);
    }
}
