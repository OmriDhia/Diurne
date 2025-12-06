<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneQuoteDetail;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\QuoteDetail;

class CloneQuoteDetailResponse implements CommandResponse
{
    public function __construct(private readonly QuoteDetail $quoteDetail)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->quoteDetail->getId(),
            'reference' => $this->quoteDetail->getReference(),
        ];
    }
}
