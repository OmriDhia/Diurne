<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CreateQuoteCarpetSpecification\CreateQuoteCarpetSpecificationCommand;
use App\Contremarque\DTO\CarpetSpecificationDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateQuoteCarpetSpecifcationController extends CommandQueryController
{
    #[Route(
        path: '/api/QuoteDetail/{quoteDetailId}/createCarpetSpecification',
        name: 'quote_carpetSpecification_creation',
        methods: ['POST']
    )]
    #[OA\Post(
        path: '/api/QuoteDetail/{quoteDetailId}/createCarpetSpecification',
        tags: ['Devis'],
        summary: 'Creates or updates a Carpet Specification',
        requestBody: new OA\RequestBody(
            description: 'Carpet Specification data',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'reference', type: 'string', maxLength: 255),
                        new OA\Property(property: 'description', type: 'string'),
                        new OA\Property(property: 'collectionId', type: 'integer'),
                        new OA\Property(property: 'modelId', type: 'integer'),
                        new OA\Property(property: 'qualityId', type: 'integer'),
                        new OA\Property(property: 'hasSpecialShape', type: 'boolean'),
                        new OA\Property(property: 'isOversized', type: 'boolean'),
                        new OA\Property(property: 'specialShapeId', type: 'integer'),
                        new OA\Property(
                            property: 'dimensions',
                            type: 'object',
                            additionalProperties: new OA\AdditionalProperties(
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'dimension_id', type: 'integer'),
                                        new OA\Property(property: 'value', type: 'string'),
                                    ]
                                )
                            )
                        ),
                        new OA\Property(
                            property: 'materials',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'material_id', type: 'integer'),
                                    new OA\Property(property: 'rate', type: 'string'),
                                ]
                            )
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Carpet Specification created or updated',
                content: new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        ref: new Model(type: CarpetSpecificationDTO::class)
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
        int $quoteDetailId,
        #[MapRequestPayload] CarpetSpecificationDTO $carpetSpecificationDTO
    ): JsonResponse {
        // Check authorization if necessary
        if (!$this->isGranted('create', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        // Dispatch command to create or update carpet specification
        $command = new CreateQuoteCarpetSpecificationCommand($quoteDetailId, $carpetSpecificationDTO);
        $response = $this->handle($command);

        return SuccessResponse::create(
            'quote_carpetSpecification_creation',
            $response->toArray()
        );
    }
}
