<?php

namespace App\Workshop\Bus\Query\GetMaterialPurchasePriceById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\MaterialPurchasePriceRepository;

class GetMaterialPurchasePricesByIdQueryHandler implements QueryHandler
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
     * @throws ResourceNotFoundException
     */
    public function __invoke(GetMaterialPurchasePricesByIdQuery $query): MaterialPurchasePricesResponse
    {
        $materialPurchasePrices = $this->repository->find($query->materialPurchasePricesId);

        if ($materialPurchasePrices === null) {
            throw new ResourceNotFoundException();
        }

        return new MaterialPurchasePricesResponse($materialPurchasePrices);
    }
}