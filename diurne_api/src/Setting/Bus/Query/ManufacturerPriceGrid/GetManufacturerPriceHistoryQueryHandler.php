<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Bus\Query\ManufacturerPriceGrid\ManufacturerPriceGridResponse;

class GetManufacturerPriceHistoryQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ManufacturerPriceGridRepository $priceGridRepository
    ) {}

    public function __invoke(GetManufacturerPriceHistoryQuery $query): array
    {
        $priceHistory = $this->priceGridRepository->findPriceHistory(
            $query->getManufacturerId(),
            $query->getQualityId(),
            $query->getLimit()
        );

        return array_map(
            fn($priceGrid) => (new ManufacturerPriceGridResponse($priceGrid))->toArray(),
            $priceHistory
        );
    }
}
