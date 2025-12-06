<?php

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\ErrorResponse;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetDesignOrderAttachment\CreateCarpetDesignOrderAttachmentCommand;
use App\Contremarque\DTO\CreateCarpetDesignOrderAttachmentRequestDto;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateCarpetDesignOrderAttachmentController extends CommandQueryController
{
    #[Route('/api/createCarpetDesignOrderAttachment', name: 'create_carpet_design_order_attachment', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'CarpetDesignOrderAttachment creation',
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
        #[MapRequestPayload] CreateCarpetDesignOrderAttachmentRequestDto $requestDTO
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new CreateCarpetDesignOrderAttachmentCommand(
            $requestDTO->getCarpetDesignOrderId(),
            $requestDTO->getAttachmentId()
        );

        try {
            $response = $this->handle($command);
            return SuccessResponse::create(
                'carpet_design_order_updated',
                $response->toArray(),
                (string)empty($response->toArray()['errors'])

            );
        } catch (Exception $e) {
            return ErrorResponse::response(
                'carpet_design_order_update_failed',
                ['errors' => [$e->getMessage()]],
                $e->getMessage(),
                'error',
                422
            );
        }
    }
}
