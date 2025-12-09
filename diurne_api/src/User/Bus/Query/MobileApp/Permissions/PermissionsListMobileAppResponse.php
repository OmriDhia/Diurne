<?php

declare(strict_types=1);

namespace App\User\Bus\Query\MobileApp\Permissions;

use App\Common\Bus\Query\QueryResponse;

final class PermissionsListMobileAppResponse implements QueryResponse
{
    /**
     * @param PermissionsMobileAppResponse[] $permissions
     */
    public function __construct(
        public readonly array $permissions
    ) {}

    public function toArray(): array
    {
        return array_map(fn(PermissionsMobileAppResponse $p) => $p->toArray(), $this->permissions);
    }
}
