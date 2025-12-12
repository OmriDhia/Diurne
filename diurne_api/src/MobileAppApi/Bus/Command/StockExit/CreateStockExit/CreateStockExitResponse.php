<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockExit\CreateStockExit;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\StockExit;

final class CreateStockExitResponse implements CommandResponse
{
    public function __construct(
        private readonly StockExit $stockExit
    ) {
    }

    public function toArray(): array
    {
        return $this->stockExit->toArray();
    }
}
