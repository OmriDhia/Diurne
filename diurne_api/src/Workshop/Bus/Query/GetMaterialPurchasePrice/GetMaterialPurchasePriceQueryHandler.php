<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetMaterialPurchasePrice;

use App\Common\Bus\Query\QueryHandler;
use App\Workshop\Repository\MaterialPurchasePriceRepository;

class GetMaterialPurchasePriceQueryHandler implements QueryHandler
{
    /**
     * @param MaterialPurchasePriceRepository $repository
     */
    public function __construct(
        private MaterialPurchasePriceRepository $repository
    )
    {
    }

    /**
     * @param GetMaterialPurchasePriceQuery $query
     * @return MaterialPurchasePriceResponse
     */
    public function __invoke(GetMaterialPurchasePriceQuery $query): MaterialPurchasePriceResponse
    {
        $materialPurchasePrices = $this->repository->findAll();

        return new MaterialPurchasePriceResponse($materialPurchasePrices);
    }
}