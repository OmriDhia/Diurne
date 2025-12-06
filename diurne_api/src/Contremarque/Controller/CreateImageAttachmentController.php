<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateImageAttachment\CreateImageAttachmentCommand;
use App\Contremarque\DTO\CreateImageAttachmentRequestDto;
use App\Contremarque\Entity\ImageAttachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateImageAttachmentController extends CommandQueryController
{
    #[Route('/api/createImageAttachment', name: 'create_image_attachment', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'ImageAttachment creation',
        content: new Model(type: ImageAttachment::class)
    )]
    #[OA\RequestBody(
        description: 'ImageAttachment data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'imageId', type: 'integer'),
                new OA\Property(property: 'attachmentId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateImageAttachmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $command = new CreateImageAttachmentCommand(
            $requestDTO->getImageId(),
            $requestDTO->getAttachmentId()
        );

        $diAttachment = $this->handle($command);

        return SuccessResponse::create(
            'create_image_attachment',
            $diAttachment->toArray(),
            'ImageAttachment created'
        );
    }
}
