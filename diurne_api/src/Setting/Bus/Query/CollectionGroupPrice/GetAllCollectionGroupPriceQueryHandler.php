<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\CollectionGroupPrice;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Common\Bus\Query\CacheableQueryHandlerTrait;

class GetAllCollectionGroupPriceQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;

    public function __construct(private readonly CollectionGroupPriceRepository $collectionGroupPriceRepository)
    {
    }

    public function __invoke(GetAllCollectionGroupPriceQuery $query): CollectionGroupPriceQueryResponse
    {
        $isFetchingAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $isFetchingAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $isFetchingAll ? null : $query->getItemsPerPage();

        $collectionGroupsPprice = $isFetchingAll 
            ? $this->collectionGroupPriceRepository->findAll() 
            : $this->collectionGroupPriceRepository->findBy([], null, $limit, $offset);

        $totalItems = $this->collectionGroupPriceRepository->count([]);

        return new CollectionGroupPriceQueryResponse(
            $collectionGroupsPprice,
            $totalItems,
            $query->getPage(),
            $query->getItemsPerPage()
        );
    }
}
