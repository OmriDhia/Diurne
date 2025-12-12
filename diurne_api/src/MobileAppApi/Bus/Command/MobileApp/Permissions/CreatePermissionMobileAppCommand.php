<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\MobileApp\Permissions;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class CreatePermissionMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,
        public readonly ?string $description = null
    ) {}
}
