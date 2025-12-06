<?php

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetDesignOrderAttachment\UpdateCarpetDesignOrderAttachmentCommand;
use App\Contremarque\DTO\UpdateCarpetDesignOrderAttachmentRequestDto;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateCarpetDesignOrderAttachmentController extends CommandQueryController
{
    #[Route('/api/updateCarpetDesignOrderAttachment/{id}', name: 'update_carpet_design_order_attachment', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'CarpetDesignOrderAttachment update',
        content: new Model(type: CarpetDesignOrderAttachment::class)
    )]
    #[OA\RequestBody(
        description: 'CarpetDesignOrderAttachment data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'carpetDesignOrderId', type: 'integer'),
                new OA\Property(property: 'attachmentId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $id,
        #[MapRequestPayload] UpdateCarpetDesignOrderAttachmentRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $command = new UpdateCarpetDesignOrderAttachmentCommand(
            $id,
            $requestDTO->getCarpetDesignOrderId(),
            $requestDTO->getAttachmentId()
        );

        $carpetDesignOrderAttachment = $this->handle($command);

        return SuccessResponse::create(
            'update_carpet_design_order_attachment',
            $carpetDesignOrderAttachment->toArray(),
            'CarpetDesignOrderAttachment updated successfully'
        );
    }
}
