<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\Command;

class AssignAddressToCustomerCommand implements Command
{
    public function __construct(
        private readonly int $addressId,
        private readonly int $customerId
    ) {
    }

    public function getAddressId(): int
    {
        return $this->addressId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }
}
