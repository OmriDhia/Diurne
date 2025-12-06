<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\DiAttachment\CreateDiAttachmentCommand;
use App\Contremarque\DTO\CreateDiAttachmentRequestDto;
use App\Contremarque\Entity\DiAttachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateDiAttachmentController extends CommandQueryController
{
    #[Route('/api/createDiAttachment', name: 'create_di_attachment', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'DiAttachment creation',
        content: new Model(type: DiAttachment::class)
    )]
    #[OA\RequestBody(
        description: 'DiAttachment data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'diId', type: 'integer'),
                new OA\Property(property: 'attachmentId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateDiAttachmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $command = new CreateDiAttachmentCommand(
            $requestDTO->getAttachmentId(),
            $requestDTO->getDiId()
        );

        $diAttachment = $this->handle($command);

        return SuccessResponse::create(
            'create_di_attachment',
            $diAttachment->toArray(),
            'DiAttachment created successfully.'
        );
    }
}
