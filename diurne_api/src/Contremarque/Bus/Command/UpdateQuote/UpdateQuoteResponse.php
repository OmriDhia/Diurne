<?php

namespace App\Contremarque\Bus\Command\UpdateQuote;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Quote;

class UpdateQuoteResponse implements CommandResponse
{
    public function __construct(private readonly Quote $quote)
    {
    }

    public function toArray(): array
    {
        return $this->quote->toArray();
    }
}
