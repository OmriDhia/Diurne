<?php

namespace App\Setting\Controller;

use App\Setting\Controller\OA\Response;
use App\Setting\Controller\OA\Tag;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\AttachmentType\DeleteAttachmentTypeCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteAttachmentTypeController extends CommandQueryController
{
    #[Route('/api/attachment-type/delete/{id}', name: 'attachment_type_delete', methods: ['DELETE'])]
    #[Response(
        response: 200,
        description: 'Attachment type deleted successfully',
        content: null
    )]
    #[Tag(name: 'Setting')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $deleteCommand = new DeleteAttachmentTypeCommand($id);
        $this->handle($deleteCommand);

        return SuccessResponse::create(
            'attachment_type_delete',
            null
        );
    }
}
