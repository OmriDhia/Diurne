<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\User\CreateUserMobileApp;

use App\Common\Bus\Command\CommandResponse;
use App\MobileAppApi\Entity\UserMobileApp;

final class CreateUserMobileAppResponse implements CommandResponse
{
    public function __construct(
        private readonly UserMobileApp $user
    ) {
    }

    public function toArray(): array
    {
        return $this->user->toArray();
    }
}
