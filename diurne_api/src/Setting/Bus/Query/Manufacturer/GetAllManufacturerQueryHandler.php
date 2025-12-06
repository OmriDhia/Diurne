<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Manufacturer;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\ManufacturerRepository;
use App\Common\Bus\Query\CacheableQueryHandlerTrait;

class GetAllManufacturerQueryHandler implements QueryHandler
{
    use CacheableQueryHandlerTrait;
    
    public function __construct(private readonly ManufacturerRepository $manufacturerRepository)
    {
    }

    public function __invoke(GetAllManufacturerQuery $query): ManufacturerQueryResponse
    {
        $isFetchingAll = empty($query->getPage()) && empty($query->getItemsPerPage());
        $offset = $isFetchingAll ? 0 : ($query->getPage() - 1) * $query->getItemsPerPage();
        $limit = $isFetchingAll ? null : $query->getItemsPerPage();

        $carriers = $isFetchingAll 
            ? $this->manufacturerRepository->findAll() 
            : $this->manufacturerRepository->findBy([], null, $limit, $offset);

        $totalItems = $this->manufacturerRepository->count([]);

        return new ManufacturerQueryResponse(
            $carriers,
            $totalItems,
            $query->getPage(),
            $query->getItemsPerPage()
        );
    }
}
