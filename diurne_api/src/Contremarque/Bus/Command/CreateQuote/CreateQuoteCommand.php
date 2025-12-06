<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateQuote;

use App\Common\Bus\Command\Command;
use App\Contremarque\Dto\CreateQuoteRequestDto;

class CreateQuoteCommand implements Command
{
    public function __construct(
        public $contremarqueId,
        public CreateQuoteRequestDto $dto
    ) {}
}
