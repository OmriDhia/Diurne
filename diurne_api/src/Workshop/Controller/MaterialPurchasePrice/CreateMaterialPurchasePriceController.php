<?php

namespace App\Workshop\Controller\MaterialPurchasePrice;


use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateMaterialPurchasePrice\CreateMaterialPurchasePriceCommand;
use App\Workshop\DTO\MaterialPurchasePrice\CreateMaterialPurchasePriceDto;
use App\Workshop\Entity\MaterialPurchasePrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateMaterialPurchasePriceController extends CommandQueryController
{
    #[Route('/api/materialPurchasePrices', name: 'material_purchase_price_create', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Material purchase price created',
        content: new Model(type: MaterialPurchasePrice::class)
    )]
    #[OA\RequestBody(
        description: 'Material purchase price data',
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
        #[MapRequestPayload] CreateMaterialPurchasePriceDto $requestDto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to create material purchase price'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $command = new CreateMaterialPurchasePriceCommand(
            materialId: $requestDto->materialId,
            price: $requestDto->price,
            productionOrderId: $requestDto->productionOrderId,
            workshopInformationId: $requestDto->workshopInformationId
        );

        try {
            $response = $this->handle($command);

            return SuccessResponse::create(
                'material_purchase_price_created',
                $response->toArray(),
                'Material purchase price created successfully.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 500, 'message' => $e->getMessage()],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}