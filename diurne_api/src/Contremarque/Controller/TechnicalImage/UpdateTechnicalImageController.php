<?php

namespace App\Contremarque\Controller\TechnicalImage;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\TechnicalImage\TechnicalImageResponse;
use App\Contremarque\Bus\Command\TechnicalImage\UpdateTechnicalImageCommand;
use App\Contremarque\DTO\UpdateTechnicalImageRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateTechnicalImageController extends CommandQueryController
{
    #[Route(
        '/api/technical-image/update/{id}',
        name: 'update_technical_image',
        methods: ['PUT']
    )]
    #[OA\Response(
        response: 200,
        description: 'Update Technical image',
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
        int                                                 $id,
        #[MapRequestPayload] UpdateTechnicalImageRequestDto $requestDTO,
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createTechnicalImage = new UpdateTechnicalImageCommand(
            $id,
            $requestDTO->imageCommandId,
            $requestDTO->imageTypeId,
            $requestDTO->name,
            $requestDTO->attachmentId,
        );

        $imageResponse = $this->handle($createTechnicalImage);

        return SuccessResponse::create(
            'technical_image_creation',
            $imageResponse->toArray()

        );
    }
}
