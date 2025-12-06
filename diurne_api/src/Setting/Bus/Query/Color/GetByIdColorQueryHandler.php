<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Color;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\ColorRepository;

/**
 * This class is responsible for handling the 'get color by ID' query.
 */
final readonly class GetByIdColorQueryHandler implements QueryHandler
{
    /**
     * Constructor with ColorRepository injection.
     *
     * @param ColorRepository $colorRepository color repository interface
     */
    public function __construct(
        private ColorRepository $colorRepository
    ) {
    }

    /**
     * Handles the 'get color by ID' query.
     *
     * @param GetByIdColorQuery $query the query object containing the color ID
     *
     * @return GetByIdColorResponse the response object with color details
     *
     * @throws ResourceNotFoundException thrown when the color is not found
     */
    public function __invoke(GetByIdColorQuery $query): GetByIdColorResponse
    {
        $color = $this->colorRepository->find((int) $query->colorId());

        if (null === $color) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdColorResponse($color);
    }
}
