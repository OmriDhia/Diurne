<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\UpdateStockEntry;

use App\Common\Bus\Command\Command;

final class UpdateStockEntryCommand implements Command
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $rnId = null,
        public readonly ?string $userId = null,
        public readonly ?float $width = null,
        public readonly ?float $height = null,
        public readonly ?string $quality = null,
        public readonly ?string $color = null,
        public readonly ?string $location = null,
        public readonly ?int $photoId = null
    ) {
    }
}
