<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CloneQuoteDetail;

use App\Common\Bus\Command\Command;

class CloneQuoteDetailCommand implements Command
{
    public function __construct(private readonly int $quoteDetailId)
    {
    }

    public function getQuoteDetailId(): int
    {
        return $this->quoteDetailId;
    }
}
