<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockExit\UpdateStockExit;

use App\Common\Bus\Command\Command;

final class UpdateStockExitCommand implements Command
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $rnId = null,
        public readonly ?string $userId = null,
        public readonly ?string $deliveryNote = null,
        public readonly ?string $destination = null
    ) {
    }
}
