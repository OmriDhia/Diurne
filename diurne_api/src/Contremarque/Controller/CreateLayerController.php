<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\Layer\CreateLayerCommand;
use App\Contremarque\DTO\CreateLayerRequestDto;
use App\Contremarque\Entity\Layer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/CarpetComposition/{carpetCompositionId}/Layer/create', name: 'layer_creation', methods: ['POST'])]
class CreateLayerController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Layer creation',
        content: new Model(type: Layer::class)
    )]
    #[OA\RequestBody(
        description: 'Layer data',
        content: new OA\JsonContent(
            required: ['layerNumber', 'remarque'],
            properties: [
                new OA\Property(property: 'layerNumber', type: 'int'),
                new OA\Property(property: 'remarque', type: 'string'),
                new OA\Property(
                    property: 'layer_details',
                    type: 'array',
                    items: new OA\Items(
                        required: ['threadId', 'color_id', 'material_id', 'pourcentage'],
                        properties: [
                            new OA\Property(property: 'threadId', type: 'int'),
                            new OA\Property(property: 'color_id', type: 'int'),
                            new OA\Property(property: 'material_id', type: 'int'),
                            new OA\Property(property: 'pourcentage', type: 'float'),
                        ],
                        type: 'object'
                    ),
                    nullable: true
                ),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(
        int $carpetCompositionId,
        #[MapRequestPayload] CreateLayerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Create Layer command
        $createLayerCommand = new CreateLayerCommand(
            $carpetCompositionId,
            $requestDTO->layerNumber,
            $requestDTO->remarque,
            $requestDTO->layer_details
        );

        $response = $this->handle($createLayerCommand);

        return SuccessResponse::create(
            'layer_creation',
            $response->toArray(),
            'Layer created successfully'

        );
    }
}
