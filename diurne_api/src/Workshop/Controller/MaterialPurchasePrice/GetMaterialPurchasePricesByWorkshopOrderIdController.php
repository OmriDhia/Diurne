<?php
declare(strict_types=1);

namespace App\Workshop\Controller\MaterialPurchasePrice;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetMaterialPurchasePriceByWorkshopOrderId\GetMaterialPurchasePricesByWorkshopOrderIdQuery;
use App\Workshop\Entity\MaterialPurchasePrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetMaterialPurchasePricesByWorkshopOrderIdController extends CommandQueryController
{
    #[Route('/api/materialPurchasePrices/workshopOrder/{workshopOrderId}', name: 'material_purchase_prices_by_workshop_order', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: MaterialPurchasePrice::class)))]))]
    #[OA\Parameter(name: 'workshopOrderId', in: 'path', description: 'Workshop order id', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $workshopOrderId): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        $response = $this->ask(new GetMaterialPurchasePricesByWorkshopOrderIdQuery($workshopOrderId));

        return SuccessResponse::create('material_purchase_prices_list', $response->toArray());
    }
}
