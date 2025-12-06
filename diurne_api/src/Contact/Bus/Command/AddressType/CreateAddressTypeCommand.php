<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\AddressType;

use App\Common\Bus\Command\Command;

class CreateAddressTypeCommand implements Command
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
