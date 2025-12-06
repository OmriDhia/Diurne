<?php

namespace App\Contremarque\Controller\ImageCommandDesignerAssignment;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\ImageCommandDesignerAssignment\DeleteImageCommandDesignerAssignmentCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/image-command/assign-designer/{id}', name: 'delete_image_command_designer_assignment', methods: ['DELETE'])]
class DeleteImageCommandDesignerAssignmentController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Delete image command designer assignment',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
    ): JsonResponse {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $query = new DeleteImageCommandDesignerAssignmentCommand($id);
        $this->handle($query);
        return SuccessResponse::create(
            'delete_image_command_designer_assignment',
            [],
            'Image Command  Designer assignment deleted successfully'

        );
    }
}
