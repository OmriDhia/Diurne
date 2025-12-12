<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\Workshop\GetWorkshop;

use App\Common\Bus\Query\Query;

final class GetWorkshopQuery implements Query
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
