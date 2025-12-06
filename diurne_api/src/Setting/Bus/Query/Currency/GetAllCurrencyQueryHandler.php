<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Currency;

use App\Common\Bus\Query\QueryHandler;
use App\Setting\Repository\CurrencyRepository;

class GetAllCurrencyQueryHandler implements QueryHandler
{
    public function __construct(private readonly CurrencyRepository $currencyRepository)
    {
    }

    public function __invoke(GetAllCurrencyQuery $query): CurrencyQueryResponse
    {
        $all_currency = $this->currencyRepository->findAll();

        return new CurrencyQueryResponse($all_currency);
    }
}
