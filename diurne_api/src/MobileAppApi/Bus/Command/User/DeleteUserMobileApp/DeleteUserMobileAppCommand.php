<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\User\DeleteUserMobileApp;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class DeleteUserMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly int $id
    ) {}
}
