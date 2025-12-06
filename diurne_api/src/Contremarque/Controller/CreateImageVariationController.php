<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\Variation\CreateImageVariationCommand;
use App\Contremarque\Bus\Command\Variation\ImageVariationResponse;
use App\Contremarque\DTO\CreateImageVariationRequestDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateImageVariationController extends CommandQueryController
{
    #[Route('/api/carpetDesignOrder/{carpetDesignOrderId}/createVariation', name: 'image_variation_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Image creation',
        content: new Model(type: ImageVariationResponse::class)
    )]
    #[OA\RequestBody(
        description: 'Image variation data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'variation_image_reference', type: 'string'),
                new OA\Property(property: 'variation', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        $carpetDesignOrderId,
        #[MapRequestPayload] CreateImageVariationRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createImageVariationCommand = new CreateImageVariationCommand(
            (int) $carpetDesignOrderId,
            $requestDTO->variation_image_reference,
            $requestDTO->variation
        );
        $imageResponse = $this->handle($createImageVariationCommand);

        return SuccessResponse::create(
            'image_variation_creation',
            $imageResponse->toArray(),
            'Image variation created successfully'
        );
    }
}
