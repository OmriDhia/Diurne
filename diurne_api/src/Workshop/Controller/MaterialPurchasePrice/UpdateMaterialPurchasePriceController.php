<?php

namespace App\Workshop\Controller\MaterialPurchasePrice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateMaterialPurchasePrice\UpdateMaterialPurchasePriceCommand;
use App\Workshop\DTO\MaterialPurchasePrice\UpdateMaterialPurchasePriceDto;
use App\Workshop\Entity\MaterialPurchasePrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateMaterialPurchasePriceController extends CommandQueryController
{
    #[Route('/api/materialPurchasePrices/{id}', name: 'material_purchase_price_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Material purchase price updated',
        content: new Model(type: MaterialPurchasePrice::class)
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Material Purchase Price ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        description: 'Material purchase price update data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "materialId", type: "integer"),
                new OA\Property(property: "price", type: "number", format: "float", example: "12.34"),
                new OA\Property(property: "productionOrderId", type: "integer"),
                new OA\Property(property: "workshopInformationId", type: "integer")
            ]
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                 $id,
        #[MapRequestPayload] UpdateMaterialPurchasePriceDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);

        }

        $command = new UpdateMaterialPurchasePriceCommand(
            id: $id,
            materialId: $requestDto->materialId,
            price: $requestDto->price,
            productionOrderId: $requestDto->productionOrderId,
            workshopInformationId: $id
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'material_purchase_price_updated',
                $response->toArray(),
                'Material purchase price updated successfully.'
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