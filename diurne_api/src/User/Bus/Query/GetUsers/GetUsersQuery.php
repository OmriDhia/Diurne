<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetUsers;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a user by their ID.
 */
final class GetUsersQuery implements Query
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
        public ?string $firstname = null,
        public ?string $lastname = null,
        public ?string $email = null,
        public ?string $profileId = null,
        public ?string $gender = null,
        public ?string $profiles = null,
        public ?bool $isActive = null,
    ) {
    }

    public function getPage(): int|null
    {
        return $this->page;
    }

    public function getItemsPerPage(): int|null
    {
        return $this->itemsPerPage;
    }

    public function getFirstname(): string|null
    {
        return $this->firstname;
    }

    public function getLastname(): string|null
    {
        return $this->lastname;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getProfileId(): string|null
    {
        return $this->profileId;
    }

    public function getGender(): string|null
    {
        return $this->gender;
    }

    public function getProfiles(): string|null
    {
        return $this->profiles;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }
}
