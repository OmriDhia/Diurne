<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\CustomerGroup;

use App\Common\Bus\Command\Command;

class CreateCustomerGroupCommand implements Command
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
