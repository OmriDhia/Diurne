<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetUserById;

use App\Common\Bus\Query\QueryResponse;

/**
 * This class represents the response data for a query to retrieve a user by their ID.
 * It includes the user's ID, username, and roles.
 */
final class GetUserByIdResponse implements QueryResponse
{
    /**
     * GetUserByIdResponse constructor.
     */
    public function __construct(
        public string $id,
        public string $gender,
        public string $email,
        public string $lastname,
        public string $firstname,
        public string $profile,
        public string $profileId,
        public array  $permissions,
        public array  $menus,
        public bool   $isActive
    )
    {
    }

    /**
     * @return (array|string)[]
     *
     * @psalm-return array{user_id: string, gender: string, email: string, lastname: string, firstname: string, profile: string, permissions: array, menus: array}
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->id,
            'gender' => $this->gender,
            'email' => $this->email,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'profile' => $this->profile,
            'profileId' => $this->profile,
            'permissions' => $this->permissions,
            'menus' => $this->menus,
            'isActive' => $this->isActive,
        ];
    }
}
