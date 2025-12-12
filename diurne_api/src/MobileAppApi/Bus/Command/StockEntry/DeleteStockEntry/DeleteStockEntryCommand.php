<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\DeleteStockEntry;

use App\Common\Bus\Command\Command;

final class DeleteStockEntryCommand implements Command
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
