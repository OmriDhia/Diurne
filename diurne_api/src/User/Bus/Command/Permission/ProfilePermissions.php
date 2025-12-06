<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\CommandResponse;

final class ProfilePermissions implements CommandResponse
{
    public function __construct(
        public string $name,
        public array $permissions
    ) {
    }

    /**
     * @return (array|string)[]
     *
     * @psalm-return array{name: string, permissions: array}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}
