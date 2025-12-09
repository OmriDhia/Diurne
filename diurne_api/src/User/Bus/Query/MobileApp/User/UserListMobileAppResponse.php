<?php

declare(strict_types=1);

namespace App\User\Bus\Query\MobileApp\User;

use App\Common\Bus\Query\QueryResponse;

final class UserListMobileAppResponse implements QueryResponse
{
    /**
     * @param UserMobileAppResponse[] $users
     */
    public function __construct(
        public readonly array $users
    ) {}

    public function toArray(): array
    {
        return array_map(fn(UserMobileAppResponse $user) => $user->toArray(), $this->users);
    }
}
