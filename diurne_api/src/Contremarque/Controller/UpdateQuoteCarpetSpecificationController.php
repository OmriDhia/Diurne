<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\UpdateQuoteCarpetSpecification\UpdateQuoteCarpetSpecificationCommand;
use App\Contremarque\DTO\UpdateCarpetSpecificationDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateQuoteCarpetSpecificationController extends CommandQueryController
{
    #[Route(
        path: '/api/QuoteDetail/{quoteDetailId}/updateCarpetSpecification/{id}',
        name: 'quote_carpetSpecification_update',
        methods: ['PUT']
    )]
    #[OA\Put(
        path: '/api/QuoteDetail/{quoteDetailId}/updateCarpetSpecification',
        tags: ['Devis'],
        summary: 'Updates a Carpet Specification',
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
                        new OA\Property(
                            property: 'dimensions',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'dimension_id', type: 'integer'),
                                    new OA\Property(property: 'value', type: 'string'),
                                ]
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
    #[OA\Tag(name: 'Devis')]
    public function __invoke(
        int $id,
        int $quoteDetailId,
        #[MapRequestPayload] UpdateCarpetSpecificationDTO $carpetSpecificationDTO
    ): JsonResponse {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new UpdateQuoteCarpetSpecificationCommand($quoteDetailId, $carpetSpecificationDTO);
        $command->setId($id);
        $response = $this->handle($command);

        return SuccessResponse::create(
            'quote_carpetSpecification_update',
            $response->toArray(),
            'Carpet Specification updated'
        );
    }
}
