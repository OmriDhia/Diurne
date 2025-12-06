<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetSpecification\UpdateCarpetSpecificationCommand;
use App\Contremarque\DTO\UpdateCarpetSpecificationDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateCarpetSpecificationController extends CommandQueryController
{
    #[Route(
        path: '/api/carpetDesignOrder/{carpetDesignOrderId}/updateCarpetSpecification/{id}',
        name: 'carpetSpecification_update',
        methods: ['PUT']
    )]
    #[OA\Put(
        path: '/api/carpetDesignOrder/{carpetDesignOrderId}/updateCarpetSpecification/{id}',
        tags: ['Contremarque'],
        summary: 'Updates a Carpet Specification',
        requestBody: new OA\RequestBody(
            description: 'Carpet Specification data',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'reference', type: 'string', maxLength: 255, nullable: true),
                        new OA\Property(property: 'description', type: 'string', nullable: true),
                        new OA\Property(property: 'collectionId', type: 'integer', nullable: true),
                        new OA\Property(property: 'modelId', type: 'integer', nullable: true),
                        new OA\Property(property: 'qualityId', type: 'integer', nullable: true),
                        new OA\Property(property: 'hasSpecialShape', type: 'boolean', nullable: true),
                        new OA\Property(property: 'isOversized', type: 'boolean', nullable: true),
                        new OA\Property(property: 'specialShapeId', type: 'integer', nullable: true),
                        new OA\Property(property: 'dimensions', type: 'object', additionalProperties: new OA\AdditionalProperties(
                            properties: [
                                new OA\Property(property: 'dimension_id', type: 'integer'),
                                new OA\Property(property: 'value', type: 'string', format: 'decimal'),
                            ]
                        )),
                        new OA\Property(property: 'materials', type: 'array', items: new OA\Items(
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'material_id', type: 'integer'),
                                new OA\Property(property: 'rate', type: 'string', format: 'decimal'),
                            ]
                        )),
                    ],
                    required: []
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Carpet Specification updated',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        ref: new Model(type: UpdateCarpetSpecificationDTO::class)
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    public function __invoke(
        int $carpetDesignOrderId,
        int $id,
        #[MapRequestPayload] UpdateCarpetSpecificationDTO $carpetSpecificationDTO
    ): JsonResponse {
        // Check authorization if necessary
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Dispatch command to update carpet specification
        $command = new UpdateCarpetSpecificationCommand($carpetDesignOrderId, $id, $carpetSpecificationDTO);
        $response = $this->handle($command);

        return SuccessResponse::create('carpet_specification_updated', $response->toArray());
    }
}
