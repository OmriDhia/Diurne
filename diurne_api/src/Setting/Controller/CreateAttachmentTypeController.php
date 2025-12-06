<?php

namespace App\Setting\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Setting\Bus\Command\AttachmentType\CreateAttachmentTypeCommand;
use App\Setting\DTO\CreateAttachmentTypeRequestDto;
use App\Setting\Entity\AttachmentType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/attachment-type/create', name: 'attachment_type_creation', methods: ['POST'])]
class CreateAttachmentTypeController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'AttachmentType creation',
        content: new Model(type: AttachmentType::class)
    )]
    #[OA\RequestBody(
        description: 'AttachmentType data',
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Setting')]
    public function __invoke(
        #[MapRequestPayload] CreateAttachmentTypeRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'setting')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $createCommand = new CreateAttachmentTypeCommand(
            $requestDTO->name
        );

        $response = $this->handle($createCommand);

        return SuccessResponse::create(
            'attachment_type_creation',
            $response->toArray()
        );
    }
}
