<?php

namespace App\Contremarque\Bus\Command\CreateQuote;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Quote;

class CreateQuoteResponse implements CommandResponse
{
    public function __construct(private readonly Quote $quote)
    {
    }

    public function toArray(): array
    {
        return $this->quote->toArray();
    }

    public function getQuote(): Quote
    {
        return $this->quote;
    }
}
