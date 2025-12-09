<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\Permissions;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class DeletePermissionMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly int $id
    ) {}
}
