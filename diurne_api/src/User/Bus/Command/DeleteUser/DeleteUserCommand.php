<?php

declare(strict_types=1);

namespace App\User\Bus\Command\DeleteUser;

use App\Common\Bus\Command\Command;

final class DeleteUserCommand implements Command
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
