<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteAddress;

use App\Common\Bus\Command\Command;

class DeleteAddressCommand implements Command
{
    public function __construct(
        private readonly int $addressId,
    ) {
    }

    public function getAddressId(): int
    {
        return $this->addressId;
    }
}
