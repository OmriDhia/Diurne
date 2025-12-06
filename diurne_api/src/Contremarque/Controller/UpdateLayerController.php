<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetComposition\Layer\UpdateLayerCommand;
use App\Contremarque\DTO\UpdateLayerRequestDto;
use App\Contremarque\Entity\Layer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/CarpetComposition/{carpetCompositionId}/Layer/{layerId}/update', name: 'layer_update', methods: ['PUT'])]
class UpdateLayerController extends CommandQueryController
{
    #[OA\Response(
        response: 200,
        description: 'Layer update',
        content: new Model(type: Layer::class)
    )]
    #[OA\RequestBody(
        description: 'Layer data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'layerNumber', type: 'int', nullable: true),
                new OA\Property(property: 'remarque', type: 'string', nullable: true),
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
        int $layerId,
        #[MapRequestPayload] UpdateLayerRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Update Layer command
        $updateLayerCommand = new UpdateLayerCommand(
            $carpetCompositionId,
            $layerId,
            $requestDTO->layerNumber,
            $requestDTO->remarque,
            $requestDTO->layer_details
        );

        $response = $this->handle($updateLayerCommand);

        return SuccessResponse::create(
            'layer_update',
            $response->toArray(),
            'Layer updated successfully'
        );
    }
}
