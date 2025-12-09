<?php

declare(strict_types=1);

namespace App\User\Bus\Query\MobileApp\Permissions;

use App\Common\Bus\Query\QueryResponse;

final class  PermissionsMobileAppResponse implements QueryResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly string $createdAt
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
        ];
    }
}
