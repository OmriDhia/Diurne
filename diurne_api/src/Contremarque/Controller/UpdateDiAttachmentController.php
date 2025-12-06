<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DiAttachment\UpdateDiAttachmentCommand;
use App\Contremarque\DTO\UpdateDiAttachmentRequestDto;
use App\Contremarque\Entity\DiAttachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateDiAttachmentController extends CommandQueryController
{
    #[Route('/api/updateDiAttachment/{id}', name: 'update_di_attachment', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'DiAttachment update',
        content: new Model(type: DiAttachment::class)
    )]
    #[OA\RequestBody(
        description: 'DiAttachment update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'attachmentId', type: 'integer'),
                new OA\Property(property: 'diId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateDiAttachmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $command = new UpdateDiAttachmentCommand(
            $id,
            $requestDTO->getAttachmentId(),
            $requestDTO->getDiId()
        );

        $diAttachment = $this->handle($command);

        return SuccessResponse::create(
            'update_di_attachment',
            $diAttachment->toArray(),
            'DiAttachment updated'
        );
    }
}
