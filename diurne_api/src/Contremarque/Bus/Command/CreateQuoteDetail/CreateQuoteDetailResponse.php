<?php

namespace App\Contremarque\Bus\Command\CreateQuoteDetail;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\QuoteDetail;

class CreateQuoteDetailResponse implements CommandResponse
{
    public function __construct(
        public QuoteDetail $quoteDetail
    ) {
    }

    public function toArray(): array
    {
        return $this->quoteDetail->toArray();
    }

    public function getId(): int|null
    {
        return $this->quoteDetail->getId();
    }

    public function getQuoteDetail(): QuoteDetail
    {
        return $this->quoteDetail;
    }
}
