<?php

namespace App\Workshop\Controller\MaterialPurchasePrice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteMaterialPurchasePrice\DeleteMaterialPurchasePriceCommand;
use App\Workshop\Entity\MaterialPurchasePrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteMaterialPurchasePriceController extends CommandQueryController
{
    #[Route('/api/materialPurchasePrices/{id}', name: 'material_purchase_price_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Material purchase price deleted',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'status', type: 'string'),
            new OA\Property(property: 'message', type: 'string'),
            new OA\Property(property: 'data', ref: new Model(type: MaterialPurchasePrice::class))
        ])
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Material Purchase Price ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        try {
            $response = $this->handle(new DeleteMaterialPurchasePriceCommand($id));

            return SuccessResponse::create(
                'material_purchase_price_deleted',
                $response->toArray(),
                'Material purchase price deleted successfully.'
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}