<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Currency;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a currency by its ID.
 */
final class GetByIdCurrencyQuery implements Query
{
    /**
     * Constructor for GetByIdCurrencyQuery.
     *
     * @param int $currencyId the unique identifier of the currency     */
    public function __construct(
        public int $currencyId
    ) {
    }

    public function currencyId(): int
    {
        return $this->currencyId;
    }
}
