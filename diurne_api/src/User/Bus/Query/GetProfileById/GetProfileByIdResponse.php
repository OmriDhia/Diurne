<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetProfileById;

use App\Common\Bus\Query\QueryResponse;

/**
 * This class represents the response data for a query to retrieve a profile by their ID.
 * It includes the profile's ID, profile name.
 */
final class GetProfileByIdResponse implements QueryResponse
{
    /**
     * GetUserByIdResponse constructor.
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $permissions,
    ) {
    }

    /**
     * @return (array|string)[]
     *
     * @psalm-return array{profile_id: string, name: string, permissions: array}
     */
    public function toArray(): array
    {
        return [
            'profile_id' => $this->id,
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}
