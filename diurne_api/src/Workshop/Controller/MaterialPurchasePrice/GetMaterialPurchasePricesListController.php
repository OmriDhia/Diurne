<?php
declare(strict_types=1);

namespace App\Workshop\Controller\MaterialPurchasePrice;


use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetMaterialPurchasePrice\GetMaterialPurchasePriceQuery;
use App\Workshop\Bus\Query\GetMaterialPurchasePriceById\GetMaterialPurchasePricesByIdQuery;
use App\Workshop\Entity\MaterialPurchasePrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetMaterialPurchasePricesListController extends CommandQueryController
{
    #[Route('/api/materialPurchasePrices', name: 'material_purchase_prices_by_workshop', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of material purchase prices for workshop information',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: MaterialPurchasePrice::class))
        )
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to view material purchase prices'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $query = new GetMaterialPurchasePriceQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'material_purchase_prices_list',
            $response->toArray()
        );
    }
}