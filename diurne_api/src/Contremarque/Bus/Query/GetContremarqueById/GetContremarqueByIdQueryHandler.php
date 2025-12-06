<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetContremarqueById;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Repository\ContremarqueRepository;

/**
 * This class is responsible for handling the 'get contremarque by ID' query.
 */
final readonly class GetContremarqueByIdQueryHandler implements QueryHandler
{
    /**
     * Constructor with ContremarqueRepository injection.
     *
     * @param ContremarqueRepository $contremarqueRepository contremarque repository interface
     */
    public function __construct(
        private ContremarqueRepository $contremarqueRepository
    ) {}

    /**
     * Handles the 'get contremarque by ID' query.
     *
     * @param GetContremarqueByIdQuery $query the query object containing the contremarque ID
     *
     * @return GetContremarqueByIdResponse the response object with contremarque details
     *
     * @throws ResourceNotFoundException thrown when the contremarque is not found
     */
    public function __invoke(GetContremarqueByIdQuery $query): GetContremarqueByIdResponse
    {
        $contremarque = $this->contremarqueRepository->findOneByIdWithRelations((int) $query->contremarqueId());
        if (null === $contremarque) {
            throw new ResourceNotFoundException();
        }

        $contremarqueData = $contremarque->toArray();

        // Convert PersistentCollection to array
        $quotes = $contremarque->getQuotes()->toArray();

        if (!empty($quotes)) {
            $prices = array_map(fn($quote) => $quote->getTotalTaxIncluded(), $quotes);
            $dates = array_map(fn($quote) => $quote->getCreatedAt(), $quotes);

            $contremarqueData['min_price'] = min($prices);
            $contremarqueData['max_price'] = max($prices);
            $contremarqueData['last_quote_date'] = max($dates)->format('Y-m-d H:i:s');
        } else {
            $contremarqueData['min_price'] = null;
            $contremarqueData['max_price'] = null;
            $contremarqueData['last_quote_date'] = null;
        }

        return new GetContremarqueByIdResponse($contremarqueData);
    }
}
