<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\CreateStockEntry;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\StockEntry;

final class CreateStockEntryResponse implements CommandResponse
{
    public function __construct(
        private readonly StockEntry $stockEntry
    ) {
    }

    public function toArray(): array
    {
        return $this->stockEntry->toArray();
    }
}
