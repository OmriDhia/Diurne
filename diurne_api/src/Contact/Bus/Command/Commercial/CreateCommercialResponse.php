<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\CommandResponse;

/**
 * CreateCommercialResponse represents the response returned after creating a user.
 * It primarily contains the unique identifier of the newly created user.
 */
final class CreateCommercialResponse implements CommandResponse
{
    public function __construct(
        public string $id,
        public string $email,
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
}
