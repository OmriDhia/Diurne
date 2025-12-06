<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteQuoteDetail;

use App\Common\Bus\Command\Command;

final class DeleteQuoteDetailCommand implements Command
{
    public function __construct(
        private readonly int $quoteId,
        private readonly int $quoteDetailId,
    ) {
    }

    public function getQuoteId(): int
    {
        return $this->quoteId;
    }

    public function getQuoteDetailId(): int
    {
        return $this->quoteDetailId;
    }
}
