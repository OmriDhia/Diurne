<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrderById;

use App\Common\Bus\Query\Query;

class GetCarpetOrderByIdQuery implements Query
{
    /**
     * @param int $clonedQuoteId
     */
    public function __construct(
        private readonly int $clonedQuoteId
    )
    {
    }

    public function getClonedQuoteId(): int
    {
        return $this->clonedQuoteId;
    }
}