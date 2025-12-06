<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetUsers;

use App\Common\Bus\Query\QueryResponse;

/**
 * This class represents the response data for a query to retrieve a user by their ID.
 * It includes the user's ID, username, and roles.
 */
final class GetUsersResponse implements QueryResponse
{
    /**
     * Constructor for GetUserByIdResponse.
     */
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $users,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, users: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'users' => $this->users,
        ];
    }
}
