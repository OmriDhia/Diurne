<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\CollectionGroup;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\CollectionGroupRepository;

class GetAllCollectionGroupsQueryHandler implements QueryHandler
{
    public function __construct(private readonly CollectionGroupRepository $collectionGroupRepository)
    {
    }

    public function __invoke(GetAllCollectionGroupsQuery $query): CollectionGroupQueryResponse
    {
        $isFetchingAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $isFetchingAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $isFetchingAll ? null : $query->getItemsPerPage();

        $collectiongroups = $isFetchingAll 
            ? $this->collectionGroupRepository->findAll() 
            : $this->collectionGroupRepository->findBy([], null, $limit, $offset);

        $totalItems = $this->collectionGroupRepository->count([]);

        return new CollectionGroupQueryResponse(
            $collectiongroups,
            $totalItems,
            $query->getPage(),
            $query->getItemsPerPage()
        );
    }
}
