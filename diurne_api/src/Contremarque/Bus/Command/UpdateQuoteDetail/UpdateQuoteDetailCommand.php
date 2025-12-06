<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateQuoteDetail;

use App\Common\Bus\Command\Command;
use App\Contremarque\DTO\UpdateQuoteDetailRequestDto;

class UpdateQuoteDetailCommand implements Command
{
    public function __construct(
        public int $quoteId,
        public int $quoteDetailId,
        public UpdateQuoteDetailRequestDto $requestDTO
    ) {
    }
}
