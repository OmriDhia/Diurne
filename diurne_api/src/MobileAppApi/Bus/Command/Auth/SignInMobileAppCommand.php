<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Auth;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class SignInMobileAppCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,
        #[Assert\NotBlank]
        public readonly string $password
    ) {}
}
