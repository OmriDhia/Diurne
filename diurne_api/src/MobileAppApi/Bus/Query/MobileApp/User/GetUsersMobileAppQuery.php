<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\MobileApp\User;

use App\Common\Bus\Query\Query;

final class GetUsersMobileAppQuery implements Query
{
    public function __construct(
        public readonly ?string $search = null
    ) {}
}
