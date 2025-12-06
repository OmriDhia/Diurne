<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignIn;

use App\Common\Bus\Command\Command;

class SignInCommand implements Command
{
    public function __construct(private readonly string $email, private readonly string $plainPassword)
    {
    }

    public function email(): string
    {
        return $this->email;
    }

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }
}
