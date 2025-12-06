<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignUp;

use App\Common\Bus\Command\CommandResponse;

/**
 * CreateUserResponse represents the response returned after creating a user.
 * It primarily contains the unique identifier of the newly created user.
 */
final class CreateUserResponse implements CommandResponse
{
    public function __construct(
        public string $id,
        public string $email,
        public bool $isActive,
    ) {
    }

    public function getUserId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
