<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\MaterialPrice;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\MaterialPriceRepository;

/**
 * This class is responsible for handling the 'get materialPrice by ID' query.
 */
final readonly class GetByIdMaterialPriceQueryHandler implements QueryHandler
{
    /**
     * Constructor with MaterialPriceRepository injection.
     *
     * @param MaterialPriceRepository $materialPriceRepository materialPrice repository interface
     */
    public function __construct(
        private MaterialPriceRepository $materialPriceRepository
    ) {
    }

    /**
     * Handles the 'get materialPrice by ID' query.
     *
     * @param GetByIdMaterialPriceQuery $query the query object containing the materialPrice ID
     *
     * @return GetByIdMaterialPriceResponse the response object with materialPrice details
     */
    public function __invoke(GetByIdMaterialPriceQuery $query): GetByIdMaterialPriceResponse
    {
        $materialPrices = $this->materialPriceRepository->findBy(['material' => $query->getMaterialId()]);

        return new GetByIdMaterialPriceResponse($materialPrices);
    }
}
