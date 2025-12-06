<?php

declare(strict_types=1);

namespace App\Contremarque\Controller\CarpetMaterial;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Command\CarpetMaterial\DeleteCarpetMaterialCommand;
use App\Contremarque\Bus\Command\CarpetMaterial\DeleteCarpetMaterialResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteCarpetMaterialController extends CommandQueryController
{
    #[Route('/api/QuoteDetail/{quoteDetailId}/CarpetMaterial/{carpetMaterialId}', name: 'quote_carpet_material_delete', methods: ['DELETE'])]
    #[OA\Response(response: 200, description: 'Carpet material deleted', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'object')]))]
    #[OA\Tag(name: 'Devis')]
    public function __invoke(int $quoteDetailId, int $carpetMaterialId): JsonResponse
    {
        if (!$this->isGranted('delete', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $command = new DeleteCarpetMaterialCommand($quoteDetailId, $carpetMaterialId);

        try {
            /** @var DeleteCarpetMaterialResponse $response */
            $response = $this->handle($command);

            return SuccessResponse::create('quote_carpet_material_deleted', $response->toArray(), 'Carpet material deleted successfully');
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
