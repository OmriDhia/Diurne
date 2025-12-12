<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockExit\DeleteStockExit;

use App\Common\Bus\Command\Command;

final class DeleteStockExitCommand implements Command
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
