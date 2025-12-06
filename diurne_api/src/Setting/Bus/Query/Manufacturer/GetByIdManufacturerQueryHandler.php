<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Manufacturer;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\ManufacturerRepository;

/**
 * This class is responsible for handling the 'get manufacturer by ID' query.
 */
final readonly class GetByIdManufacturerQueryHandler implements QueryHandler
{
    /**
     * Constructor with ManufacturerRepository injection.
     *
     * @param ManufacturerRepository $manufacturerRepository manufacturer repository interface
     */
    public function __construct(
        private ManufacturerRepository $manufacturerRepository
    ) {
    }

    /**
     * Handles the 'get manufacturer by ID' query.
     *
     * @param GetByIdManufacturerQuery $query the query object containing the manufacturer ID
     *
     * @return GetByIdManufacturerResponse the response object with manufacturer details
     *
     * @throws ResourceNotFoundException thrown when the manufacturer is not found
     */
    public function __invoke(GetByIdManufacturerQuery $query): GetByIdManufacturerResponse
    {
        $manufacturer = $this->manufacturerRepository->find((int) $query->manufacturerId());

        if (null === $manufacturer) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdManufacturerResponse($manufacturer);
    }
}
