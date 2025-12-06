<?php
declare(strict_types=1);

namespace App\Workshop\Controller\MaterialPurchasePrice;


use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetMaterialPurchasePriceById\GetMaterialPurchasePricesByIdQuery;
use App\Workshop\Entity\MaterialPurchasePrice;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetMaterialPurchasePricesByIdController extends CommandQueryController
{
    #[Route('/api/materialPurchasePrices/{id}', name: 'material_purchase_prices_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of material purchase prices for workshop information',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: MaterialPurchasePrice::class))
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Workshop Information ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'workshop')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to view material purchase prices'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $query = new GetMaterialPurchasePricesByIdQuery($id);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'material_purchase_prices_list',
            $response->toArray()
        );
    }
}