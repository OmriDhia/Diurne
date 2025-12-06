<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Currency;

use App\Common\Bus\Query\QueryHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\CurrencyRepository;

/**
 * This class is responsible for handling the 'get currency by ID' query.
 */
final readonly class GetByIdCurrencyQueryHandler implements QueryHandler
{
    /**
     * Constructor with CurrencyRepository injection.
     *
     * @param CurrencyRepository $currencyRepository currency repository interface
     */
    public function __construct(
        private CurrencyRepository $currencyRepository
    ) {
    }

    /**
     * Handles the 'get currency by ID' query.
     *
     * @param GetByIdCurrencyQuery $query the query object containing the currency ID
     *
     * @return GetByIdCurrencyResponse the response object with currency details
     *
     * @throws ResourceNotFoundException thrown when the currency is not found
     */
    public function __invoke(GetByIdCurrencyQuery $query): GetByIdCurrencyResponse
    {
        $currency = $this->currencyRepository->find((int) $query->currencyId());

        if (null === $currency) {
            throw new ResourceNotFoundException();
        }

        return new GetByIdCurrencyResponse($currency);
    }
}
