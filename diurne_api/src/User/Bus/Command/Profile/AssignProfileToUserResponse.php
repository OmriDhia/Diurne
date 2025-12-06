<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\CommandResponse;

final class AssignProfileToUserResponse implements CommandResponse
{
    public function __construct(
        public string $profile,
        public string $email
    ) {
    }

    public function getProfile(): string
    {
        return $this->profile;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string[]
     *
     * @psalm-return array{profile: string, email: string}
     */
    public function toArray(): array
    {
        return [
            'profile' => $this->profile,
            'email' => $this->email,
        ];
    }
}
