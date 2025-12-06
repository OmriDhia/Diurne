<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Image\ImageResponse;
use App\Contremarque\Bus\Command\Image\UpdateImageCommand;
use App\Contremarque\DTO\UpdateImageRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateImageController extends CommandQueryController
{
    #[Route('/api/updateImage/{id}', name: 'image_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Image update',
        content: new Model(type: ImageResponse::class)
    )]
    #[OA\RequestBody(
        description: 'Image data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'image_reference', type: 'string', nullable: true),
                new OA\Property(property: 'carpetDesignOrderId', type: 'integer'),
                new OA\Property(property: 'imageTypeId', type: 'integer'),
                new OA\Property(property: 'isValidated', type: 'boolean', nullable: true),
                new OA\Property(property: 'hasError', type: 'boolean', nullable: true),
                new OA\Property(property: 'error', type: 'string', nullable: true),
                new OA\Property(property: 'commentaire', type: 'string', nullable: true),
                new OA\Property(property: 'validatedAt', type: 'string', format: 'date-time', nullable: true),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateImageRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $updateImageCommand = new UpdateImageCommand(
            $id,
            $requestDTO->image_reference,
            $requestDTO->carpetDesignOrderId,
            $requestDTO->imageTypeId,
            $requestDTO->isValidated,
            $requestDTO->hasError,
            $requestDTO->error,
            $requestDTO->commentaire,
            $requestDTO->validatedAt
        );
        $imageResponse = $this->handle($updateImageCommand);

        return SuccessResponse::create(
            'image_update',
            $imageResponse->toArray(),
            'Image updated successfully'
        );
    }
}
