<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportCondition;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\TransportConditionRepository;

/**
 * This class is responsible for handling the 'get transportCondition by ID' query.
 */
final readonly class GetByIdTransportConditionQueryHandler implements QueryHandler
{
    /**
     * Constructor with TransportConditionRepository injection.
     *
     * @param TransportConditionRepository $transportConditionRepository transportCondition repository interface
     */
    public function __construct(
        private TransportConditionRepository $transportConditionRepository
    ) {
    }

    /**
     * Handles the 'get transportCondition by ID' query.
     *
     * @param GetByIdTransportConditionQuery $query the query object containing the transportCondition ID
     *
     * @return GetByIdTransportConditionResponse the response object with transportCondition details
     *
     * @throws ResourceNotFoundException thrown when the transportCondition is not found
     */
    public function __invoke(GetByIdTransportConditionQuery $query): GetByIdTransportConditionResponse
    {
        $transportCondition = $this->transportConditionRepository->find((int) $query->getTransportConditionId());

        if (null === $transportCondition) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdTransportConditionResponse($transportCondition);
    }
}
