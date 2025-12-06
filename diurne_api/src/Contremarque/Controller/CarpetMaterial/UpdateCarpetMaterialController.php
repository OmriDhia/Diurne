<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\CarpetMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetMaterial\UpdateCarpetMaterialCommand;
use App\Contremarque\DTO\UpdateCarpetMaterialRequestDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCarpetMaterialController extends CommandQueryController
{
    #[Route('/api/QuoteDetail/{quoteDetailId}/CarpetMaterial/{carpetMaterialId}', name: 'quote_carpet_material_update', methods: ['PUT'])]
    #[OA\Patch(
        path: '/api/QuoteDetail/{quoteDetailId}/CarpetMaterial/{carpetMaterialId}',
        tags: ['Devis'],
        summary: 'Updates a carpet material rate belonging to a quote detail',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(type: 'object', properties: [new OA\Property(property: 'rate', type: 'string')])
            )
        ),
        responses: [new OA\Response(response: 200, description: 'Updated')]
    )]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(int $quoteDetailId, int $carpetMaterialId, #[MapRequestPayload] UpdateCarpetMaterialRequestDto $requestDto): JsonResponse
    {
        if (!$this->isGranted('update', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $command = new UpdateCarpetMaterialCommand($quoteDetailId, $carpetMaterialId, $requestDto->rate, $requestDto->materialId);
        $response = $this->handle($command);

        return SuccessResponse::create('quote_carpet_material_updated', $response->toArray(), 'Carpet material updated successfully');
    }
}
