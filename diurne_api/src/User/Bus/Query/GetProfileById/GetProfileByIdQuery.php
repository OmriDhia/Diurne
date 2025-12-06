<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetProfileById;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a profile by their ID.
 */
final class GetProfileByIdQuery implements Query
{
    /**
     * Constructor for GetProfileByIdQuery.
     *
     * @param string $profileId the unique identifier of the profile
     */
    public function __construct(
        public string $profileId
    ) {
    }

    public function profileId(): string
    {
        return $this->profileId;
    }
}
