<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifGroup;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\TarifGroupRepository;
use App\Common\Bus\Query\CacheableQueryHandlerTrait;

class GetAllTarifGroupsQueryHandler implements QueryHandler 
{
    use CacheableQueryHandlerTrait;

    public function __construct(private readonly TarifGroupRepository $tarifGroupRepository)
    {
    }
    
    public function __invoke(GetAllTarifGroupsQuery $query): TarifGroupQueryResponse
    {
        $isFetchingAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $isFetchingAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $isFetchingAll ? null : $query->getItemsPerPage();

        $tarifGroups = $isFetchingAll 
            ? $this->tarifGroupRepository->findAll() 
            : $this->tarifGroupRepository->findBy([], null, $limit, $offset);

        $totalItems = $this->tarifGroupRepository->count([]);

        return new TarifGroupQueryResponse(
            $tarifGroups,
            $totalItems,
            $query->getPage(),
            $query->getItemsPerPage()
        );
    }
}
