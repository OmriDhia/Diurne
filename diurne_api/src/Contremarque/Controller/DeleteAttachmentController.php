<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DeleteAttachment\DeleteAttachmentCommand;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteAttachmentController extends CommandQueryController
{
    #[Route('/api/attachment/{id}', name: 'delete_attachment_by_id', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Attachment deleted successfully',
        content: null
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $deleteAttachment = new DeleteAttachmentCommand($id);
        $this->handle($deleteAttachment);

        return SuccessResponse::create(
            'delete_attachment_by_id',
            [],
            'Attachment deleted successfully'

        );
    }
}
