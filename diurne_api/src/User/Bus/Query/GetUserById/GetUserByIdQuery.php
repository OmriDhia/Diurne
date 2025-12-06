<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetUserById;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a user by their ID.
 */
final class GetUserByIdQuery implements Query
{
    /**
     * Constructor for GetUserByIdQuery.
     *
     * @param string $userId the unique identifier of the user
     */
    public function __construct(
        public string $userId
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
