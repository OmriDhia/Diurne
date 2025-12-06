<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrderDetailById;

use App\Common\Bus\Query\Query;

class GetCarpetOrderDetailByIdQuery implements Query
{
    /**
     * @param int $clonedQuoteDetailId
     */
    public function __construct(
        private readonly int $clonedQuoteDetailId
    )
    {
    }

    public function getClonedQuoteDetailId(): int
    {
        return $this->clonedQuoteDetailId;
    }
}