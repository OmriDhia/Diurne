<?php

declare(strict_types=1);

namespace App\MobileAppApi\DTO\User;

use Symfony\Component\Validator\Constraits as Assert;

final class CreateUserMobileAppRequestDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly int $permissionId,
        public readonly bool $isActive,
        public readonly ?string $picture = null,
    ) {
    }
}
