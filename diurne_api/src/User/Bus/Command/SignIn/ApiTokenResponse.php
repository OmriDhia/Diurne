<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignIn;

use App\Common\Bus\Command\CommandResponse;

final class ApiTokenResponse implements CommandResponse
{
    public function __construct(
        public int $user_id,
        public string $token
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return (int|string)[]
     *
     * @psalm-return array{user_id: int, token: string}
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'token' => $this->token,
        ];
    }
}
