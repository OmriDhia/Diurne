<?php

namespace App\Contremarque\Controller\TechnicalImage;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\TechnicalImage\CreateTechnicalImageCommand;
use App\Contremarque\Bus\Command\TechnicalImage\TechnicalImageResponse;
use App\Contremarque\DTO\CreateTechnicalImageRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateTechnicalImageController extends CommandQueryController
{
    #[Route(
        '/api/technical-image/create',
        name: 'create_technical_image',
        methods: ['POST']
    )]
    #[OA\Response(
        response: 200,
        description: 'Create Technical image',
        content: new Model(type: TechnicalImageResponse::class)
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'imageCommandId', type: 'integer'),
                new OA\Property(property: 'imageTypeId', type: 'integer'),
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'attachmentId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        #[MapRequestPayload] CreateTechnicalImageRequestDto $requestDTO,
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createTechnicalImage = new CreateTechnicalImageCommand(
            $requestDTO->imageCommandId,
            $requestDTO->imageTypeId,
            $requestDTO->name,
            $requestDTO->attachmentId,
        );

        $imageResponse = $this->handle($createTechnicalImage);

        return SuccessResponse::create(
            'technical_image_creation',
            $imageResponse->toArray(),
            'Technical image created successfully'
        );
    }
}
