<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteDetailCarpetDesignOrderOptions;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a quoteDetail by its ID.
 */
final class GetQuoteDetailCarpetDesignOrderOptionsQuery implements Query
{
    /**
     * Constructor for GetQuoteDetailCarpetDesignOrderOptionsQuery.
     *
     * @param int $quoteDetailId the unique identifier of the quoteDetail
     */
    public function __construct(
        public int $quoteDetailId
    ) {}

    public function quoteDetailId(): int
    {
        return $this->quoteDetailId;
    }
}
