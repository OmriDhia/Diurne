<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\ManufacturerPriceGridRepository;

class GetAllManufacturerPriceGridsQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ManufacturerPriceGridRepository $priceGridRepository
    )
    {
    }

    public function __invoke(GetAllManufacturerPriceGridsQuery $query): ManufacturerPriceGridQueryResponse
    {
        $criteria = [];
        $page = $query->getPage() ?? 1;
        $itemsPerPage = $query->getItemsPerPage() ?? 25;
        $offset = ($page - 1) * $itemsPerPage;

        // Limiter le nombre maximum d'éléments par page
        $itemsPerPage = min($itemsPerPage, 100);
        if ($query->getManufacturerId()) {
            $criteria['manufacturer'] = $query->getManufacturerId();
        }

        if ($query->getTarifGroupId()) {
            $criteria['tarifGroup'] = $query->getTarifGroupId();
        }

        if ($query->getQualityId()) {
            $criteria['quality'] = $query->getQualityId();
        }

        /*if ($query->isOnlyActive()) {
            $criteria['isActive'] = true;
        }*/

        $isFetchingAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $isFetchingAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $isFetchingAll ? null : $query->getItemsPerPage();

        $priceGrids = $isFetchingAll
            ? $this->priceGridRepository->findBy($criteria, ['tarifGroup' => 'DESC', 'quality' => 'ASC'])
            : $this->priceGridRepository->findBy($criteria, ['tarifGroup' => 'DESC', 'quality' => 'ASC'], $limit, $offset);

        $totalItems = $this->priceGridRepository->count($criteria);

        return new ManufacturerPriceGridQueryResponse(
            $priceGrids,
            $totalItems,
            $query->getPage(),
            $query->getItemsPerPage()
        );
    }
}
