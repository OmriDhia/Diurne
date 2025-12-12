<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\MobileApp\Permissions;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdatePermissionMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $description = null
    ) {}
}
