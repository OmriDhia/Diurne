<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CheckSpecificationCoherence;

use App\Common\Bus\Query\Query;

final class CheckSpecificationCoherenceQuery implements Query
{
    public function __construct(
        private int $carpetDesignOrderId,
        private int $quoteDetailId
    )
    {
    }

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }

    public function getQuoteDetailId(): int
    {
        return $this->quoteDetailId;
    }
}