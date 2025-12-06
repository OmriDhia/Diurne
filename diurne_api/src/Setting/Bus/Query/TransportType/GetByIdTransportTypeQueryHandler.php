<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportType;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\TransportTypeRepository;

/**
 * This class is responsible for handling the 'get transportType by ID' query.
 */
final readonly class GetByIdTransportTypeQueryHandler implements QueryHandler
{
    /**
     * Constructor with TransportTypeRepository injection.
     *
     * @param TransportTypeRepository $transportTypeRepository transportType repository interface
     */
    public function __construct(
        private TransportTypeRepository $transportTypeRepository
    ) {
    }

    /**
     * Handles the 'get transportType by ID' query.
     *
     * @param GetByIdTransportTypeQuery $query the query object containing the transportType ID
     *
     * @return GetByIdTransportTypeResponse the response object with transportType details
     *
     * @throws ResourceNotFoundException thrown when the transportType is not found
     */
    public function __invoke(GetByIdTransportTypeQuery $query): GetByIdTransportTypeResponse
    {
        $transportType = $this->transportTypeRepository->find((int) $query->transportTypeId());

        if (null === $transportType) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdTransportTypeResponse($transportType);
    }
}
