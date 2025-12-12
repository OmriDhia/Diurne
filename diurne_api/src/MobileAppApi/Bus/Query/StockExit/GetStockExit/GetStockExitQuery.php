<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\StockExit\GetStockExit;

use App\Common\Bus\Query\Query;

final class GetStockExitQuery implements Query
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
