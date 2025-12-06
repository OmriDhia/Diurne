<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Bus\Query\ManufacturerPriceGrid\ManufacturerPriceGridResponse;

class GetManufacturerPriceGridByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ManufacturerPriceGridRepository $priceGridRepository
    ) {}

    public function __invoke(GetManufacturerPriceGridByIdQuery $query): ManufacturerPriceGridResponse
    {
        $priceGrid = $this->priceGridRepository->find($query->getId());

        if (!$priceGrid) {
            throw new ResourceNotFoundException('Price grid not found.');
        }

        return new ManufacturerPriceGridResponse($priceGrid);
    }
}
