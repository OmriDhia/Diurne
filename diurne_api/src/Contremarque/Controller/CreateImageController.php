<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Image\CreateImageCommand;
use App\Contremarque\Bus\Command\Image\ImageResponse;
use App\Contremarque\DTO\CreateImageRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateImageController extends CommandQueryController
{
    #[Route('/api/createImage', name: 'image_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Image creation',
        content: new Model(type: ImageResponse::class)
    )]
    #[OA\RequestBody(
        description: 'Image data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'image_reference', type: 'string'),
                new OA\Property(property: 'carpetDesignOrderId', type: 'integer'),
                new OA\Property(property: 'imageTypeId', type: 'integer'),
                new OA\Property(property: 'isValidated', type: 'boolean'),
                new OA\Property(property: 'hasError', type: 'boolean', nullable: true),
                new OA\Property(property: 'error', type: 'string', nullable: true),
                new OA\Property(property: 'commentaire', type: 'string', nullable: true),
                new OA\Property(property: 'validatedAt', type: 'string', format: 'date-time'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateImageRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createImageCommand = new CreateImageCommand(
            $requestDTO->image_reference,
            $requestDTO->carpetDesignOrderId,
            $requestDTO->imageTypeId,
            $requestDTO->isValidated,
            $requestDTO->hasError,
            $requestDTO->error,
            $requestDTO->commentaire,
            $requestDTO->validatedAt
        );
        $imageResponse = $this->handle($createImageCommand);

        return SuccessResponse::create(
            'image_creation',
            $imageResponse->toArray(),
            'Image created successfully'
        );
    }
}
