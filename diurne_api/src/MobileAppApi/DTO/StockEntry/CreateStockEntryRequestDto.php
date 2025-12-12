<?php

declare(strict_types=1);

namespace App\MobileAppApi\DTO\StockEntry;

final class CreateStockEntryRequestDto
{
    public function __construct(
        public readonly int $rnId,
        public readonly string $location,
        public readonly ?int $userId = null
    ) {
    }
}
