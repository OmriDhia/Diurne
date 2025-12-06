<?php

namespace App\Contremarque\Bus\Command\UpdateQuote;

use App\Common\Bus\Command\Command;
use App\Contremarque\DTO\UpdateQuoteRequestDto;

class UpdateQuoteCommand implements Command
{
    public function __construct(
        public int $contremarqueId,
        public int $quoteId,
        public UpdateQuoteRequestDto $dto
    ) {
    }
}
