<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Carrier;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\CarrierRepository;

/**
 * This class is responsible for handling the 'get carrier by ID' query.
 */
final readonly class GetByIdCarrierQueryHandler implements QueryHandler
{
    /**
     * Constructor with CarrierRepository injection.
     *
     * @param CarrierRepository $carrierRepository carrier repository interface
     */
    public function __construct(
        private CarrierRepository $carrierRepository
    ) {
    }

    /**
     * Handles the 'get carrier by ID' query.
     *
     * @param GetByIdCarrierQuery $query the query object containing the carrier ID
     *
     * @return GetByIdCarrierResponse the response object with carrier details
     *
     * @throws ResourceNotFoundException thrown when the carrier is not found
     */
    public function __invoke(GetByIdCarrierQuery $query): GetByIdCarrierResponse
    {
        $carrier = $this->carrierRepository->find((int) $query->carrierId());

        if (null === $carrier) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdCarrierResponse($carrier);
    }
}
