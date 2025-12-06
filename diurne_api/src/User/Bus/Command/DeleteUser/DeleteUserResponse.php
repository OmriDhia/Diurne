<?php

declare(strict_types=1);

namespace App\User\Bus\Command\DeleteUser;

use App\Common\Bus\Command\CommandResponse;

final class DeleteUserResponse implements CommandResponse
{
    public function __construct(
        public string $id,
        public string $email,
    ) {
    }

    /**
     * @return string[]
     *
     * @psalm-return array{user_id: string, email: string}
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->id,
            'email' => $this->email,
        ];
    }
}
