<?php

declare(strict_types=1);

namespace App\User\Bus\Query\MobileApp\User;

use App\Common\Bus\Query\QueryResponse;

final class UserMobileAppResponse implements QueryResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly bool $isActive,
        public readonly ?string $picture,
        public readonly array $permission,
        public readonly string $createdAt
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'isActive' => $this->isActive,
            'picture' => $this->picture,
            'permission' => $this->permission,
            'createdAt' => $this->createdAt,
        ];
    }
}
