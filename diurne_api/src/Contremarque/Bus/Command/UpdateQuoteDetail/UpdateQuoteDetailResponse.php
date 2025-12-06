<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateQuoteDetail;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\QuoteDetail;

class UpdateQuoteDetailResponse implements CommandResponse
{
    public function __construct(
        public QuoteDetail $quoteDetail
    ) {
    }

    public function toArray(): array
    {
        return $this->quoteDetail->toArray();
    }
}
