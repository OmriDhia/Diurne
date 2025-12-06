<?php

declare(strict_types=1);

namespace App\User\Bus\Command\User;

use App\Common\Bus\Command\CommandResponse;

final class UpdateUserResponse implements CommandResponse
{
    public function __construct(
        public string $id,
        public string $email,
        public string $firstname,
        public string $lastname,
        public bool $isActive,
    ) {
    }

    /**
     * @return string[]
     *
     * @psalm-return array{user_id: string, email: string, firstname: string, lastname: string, is_active: bool}
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'is_active' => $this->isActive,
        ];
    }
}
