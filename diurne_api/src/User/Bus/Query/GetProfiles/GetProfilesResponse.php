<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetProfiles;

use App\Common\Bus\Query\QueryResponse;

/**
 * This class represents the response data for a query to retrieve a user by their ID.
 * It includes the user's ID, username, and roles.
 */
final class GetProfilesResponse implements QueryResponse
{
    /**
     * GetProfilesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $profiles
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{profiles: array}
     */
    public function toArray(): array
    {
        return [
            'profiles' => $this->profiles,
        ];
    }
}
