<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteById;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a quote by its ID.
 */
final class GetQuoteByIdQuery implements Query
{
    /**
     * Constructor for GetQuoteByIdQuery.
     *
     * @param int $quoteId the unique identifier of the quote
     */
    public function __construct(
        public int $quoteId
    ) {
    }

    public function quoteId(): int
    {
        return $this->quoteId;
    }
}
