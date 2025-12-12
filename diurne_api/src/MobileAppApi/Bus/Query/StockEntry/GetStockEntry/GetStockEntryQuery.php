<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\StockEntry\GetStockEntry;

use App\Common\Bus\Query\Query;

final class GetStockEntryQuery implements Query
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
