<?php

namespace App\Contremarque\Controller\ImageCommand;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ImageCommand\DeleteImageCommandCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/image-command/{id}', name: 'delete_image_command', methods: ['DELETE'])]
class DeleteImageCommandController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Delete image command',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
    ): JsonResponse {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new DeleteImageCommandCommand($id);
        $this->handle($query);
        return SuccessResponse::create(
            'delete_image_command',
            [],
            'ImageCommand deleted successfully'

        );
    }
}
