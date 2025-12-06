<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\ManufacturerPriceGridRepository;

class GetAvailableYearsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ManufacturerPriceGridRepository $priceGridRepository
    ) {}

    public function __invoke(GetAvailableYearsQuery $query): AvailableYearsResponse
    {
        if ($query->getManufacturerId()) {
            $years = $this->priceGridRepository->findAvailableTarifGroupsForManufacturer($query->getManufacturerId());
        } else {
            $years = $this->priceGridRepository->findAvailableTarifGroups();
        }

        return new AvailableYearsResponse($years);
    }
}
