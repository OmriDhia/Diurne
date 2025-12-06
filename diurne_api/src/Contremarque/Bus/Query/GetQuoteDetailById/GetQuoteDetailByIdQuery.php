<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteDetailById;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a quoteDetail by its ID.
 */
final class GetQuoteDetailByIdQuery implements Query
{
    /**
     * Constructor for GetQuoteDetailByIdQuery.
     *
     * @param int $quoteDetailId the unique identifier of the quoteDetail
     */
    public function __construct(
        public int $quoteDetailId
    ) {
    }

    public function quoteDetailId(): int
    {
        return $this->quoteDetailId;
    }
}
