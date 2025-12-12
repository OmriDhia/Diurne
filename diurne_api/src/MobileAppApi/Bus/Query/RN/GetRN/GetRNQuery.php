<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\RN\GetRN;

use App\Common\Bus\Query\Query;

final class GetRNQuery implements Query
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
