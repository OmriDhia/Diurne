<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetPermissions;

use App\Common\Bus\Query\QueryResponse;

/**
 * This class represents the response data for a query to retrieve a user by their ID.
 * It includes the user's ID, username, and roles.
 */
final class GetPermissionsResponse implements QueryResponse
{
    /**
     * GetProfilesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $permissions
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{permissions: array}
     */
    public function toArray(): array
    {
        return [
            'permissions' => $this->permissions,
        ];
    }
}
