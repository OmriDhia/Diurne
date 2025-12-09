<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\User;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
        #[Assert\NotBlank]
        public readonly string $password,
        #[Assert\NotBlank]
        public readonly int $permissionId,
        public readonly bool $isActive = true,
        public readonly ?string $picture = null
    ) {}
}
