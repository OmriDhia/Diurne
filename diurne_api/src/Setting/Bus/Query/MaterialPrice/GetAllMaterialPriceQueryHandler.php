<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\MaterialPrice;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\MaterialPriceRepository;
use App\Common\Bus\Query\CacheableQueryHandlerTrait;
use App\Setting\Entity\MaterialPrice;

class GetAllMaterialPriceQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(private readonly MaterialPriceRepository $materialPriceRepository)
    {
    }

    public function __invoke(GetAllMaterialPriceQuery $query): MaterialPriceQueryResponse
    {
        $getAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $getAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $getAll ? null : $query->getItemsPerPage();

        if ($getAll) {
            $cacheKey = 'material_prices_all';

            if ($query->isForceRefresh()) {
                $this->clearCache($cacheKey);
            }

            $materialPricesData = $this->getCachedResult(
                $cacheKey,
                fn() => $this->fetchAndMapMaterialPrices(),
                3600
            );

            $totalItems = count($materialPricesData);
        } else {
            $materialPricesData = $this->materialPriceRepository->findBy([], null, $limit, $offset);
            $totalItems = $this->materialPriceRepository->count([]);
        }

        return new MaterialPriceQueryResponse(
            $materialPricesData,
            $totalItems,
            $query->getPage(),
            $getAll ? $totalItems : $query->getItemsPerPage()
        );
    }

    /**
     * Fetches all MaterialPrice entities and maps them to an array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAndMapMaterialPrices(): array
    {
        $materialPrices = $this->materialPriceRepository->findAll();

        return array_map(
            fn(MaterialPrice $mp) => $mp->toArray(),
            $materialPrices
        );
    }
}
