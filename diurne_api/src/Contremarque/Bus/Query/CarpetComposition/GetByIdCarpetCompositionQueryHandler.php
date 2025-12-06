<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\CarpetCompositionRepository;

/**
 * This class is responsible for handling the 'get carpetComposition by ID' query.
 */
final readonly class GetByIdCarpetCompositionQueryHandler implements QueryHandler
{
    /**
     * Constructor with CarpetCompositionRepository injection.
     *
     * @param CarpetCompositionRepository $carpetCompositionRepository carpetComposition repository interface
     */
    public function __construct(
        private CarpetCompositionRepository $carpetCompositionRepository
    ) {
    }

    /**
     * Handles the 'get carpetComposition by ID' query.
     *
     * @param GetByIdCarpetCompositionQuery $query the query object containing the carpetComposition ID
     *
     * @return GetByIdCarpetCompositionResponse the response object with carpetComposition details
     *
     * @throws ResourceNotFoundException thrown when the carpetComposition is not found
     */
    public function __invoke(GetByIdCarpetCompositionQuery $query): GetByIdCarpetCompositionResponse
    {
        $carpetComposition = $this->carpetCompositionRepository->find((int) $query->carpetCompositionId());

        if (null === $carpetComposition) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdCarpetCompositionResponse($carpetComposition);
    }
}
