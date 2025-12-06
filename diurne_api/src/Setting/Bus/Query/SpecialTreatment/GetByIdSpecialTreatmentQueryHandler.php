<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\SpecialTreatment;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\SpecialTreatmentRepository;

/**
 * This class is responsible for handling the 'get specialTreatment by ID' query.
 */
final readonly class GetByIdSpecialTreatmentQueryHandler implements QueryHandler
{
    /**
     * Constructor with SpecialTreatmentRepository injection.
     *
     * @param SpecialTreatmentRepository $specialTreatmentRepository specialTreatment repository interface
     */
    public function __construct(
        private SpecialTreatmentRepository $specialTreatmentRepository
    ) {
    }

    /**
     * Handles the 'get specialTreatment by ID' query.
     *
     * @param GetByIdSpecialTreatmentQuery $query the query object containing the specialTreatment ID
     *
     * @return GetByIdSpecialTreatmentResponse the response object with specialTreatment details
     *
     * @throws ResourceNotFoundException thrown when the specialTreatment is not found
     */
    public function __invoke(GetByIdSpecialTreatmentQuery $query): GetByIdSpecialTreatmentResponse
    {
        $specialTreatment = $this->specialTreatmentRepository->find((int) $query->getSpecialTreatmentId());

        if (null === $specialTreatment) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdSpecialTreatmentResponse($specialTreatment);
    }
}
