<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\AttachmentType\UpdateAttachmentTypeCommand;
use App\Setting\DTO\UpdateAttachmentTypeRequestDto;
use App\Setting\Entity\AttachmentType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/attachment-type/update/{id}', name: 'attachment_type_update', methods: ['PUT'])]
class UpdateAttachmentTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'AttachmentType update',
        content: new Model(type: AttachmentType::class)
    )]
    #[OA\RequestBody(
        description: 'AttachmentType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateAttachmentTypeRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $updateCommand = new UpdateAttachmentTypeCommand(
            $id,
            $requestDTO->name
        );

        $response = $this->handle($updateCommand);

        return SuccessResponse::create(
            'attachment_type_update',
            $response->toArray()
        );
    }
}
