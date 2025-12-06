<?php

namespace App\Setting\Bus\Query\GetAllPriceType;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\PriceTypeRepository;

class GetAllPriceTypesQueryHandler implements QueryHandler
{
    public function __construct(private readonly PriceTypeRepository $priceTypeRepository)
    {
    }

    public function __invoke(GetAllPriceTypesQuery $query): GetAllPriceTypesResponse
    {
        // Fetch all price types from the repository
        $priceTypes = $this->priceTypeRepository->findAll();

        return new GetAllPriceTypesResponse($priceTypes);
    }
}
