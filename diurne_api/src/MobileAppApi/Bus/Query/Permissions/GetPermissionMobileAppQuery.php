<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Query\Permissions;

use App\Common\Bus\Query\Query;

final class GetPermissionMobileAppQuery implements Query
{
    public function __construct(
        public readonly ?int $id = null
    ) {}
}
