<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\MobileApp\User;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateUserMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?bool $isActive = null,
        public readonly ?int $permissionId = null,
        public readonly ?string $picture = null
    ) {}
}
