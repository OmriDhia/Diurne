<?php

declare(strict_types=1);

namespace App\User\Bus\Query\HasPermissionTo;

use App\Common\Bus\Query\Query;

final class HasPermissionToQuery implements Query
{
    public function __construct(
        public string $permissionId,
        public string $userId,
    ) {
    }

    public function getPermissionId(): string
    {
        return $this->permissionId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
