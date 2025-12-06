<?php

namespace App\Contremarque\Bus\Command\UpdateQuoteCarpetSpecification;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\QuoteDetail;

class UpdateQuoteCarpetSpecificationResponse implements CommandResponse
{
    public function __construct(
        private readonly CarpetSpecification $carpetSpecification,
        private readonly QuoteDetail $quoteDetail
    ) {
    }

    public function toArray(): array
    {
        return [
            'quoteDetailId' => $this->quoteDetail->getId(),
            'carpetSpecification' => $this->carpetSpecification->toArray(),
        ];
    }
}
