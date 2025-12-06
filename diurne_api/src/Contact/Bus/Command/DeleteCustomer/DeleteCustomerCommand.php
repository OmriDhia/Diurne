<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteCustomer;

use App\Common\Bus\Command\Command;
use App\User\Entity\User;

class DeleteCustomerCommand implements Command
{
    public function __construct(
        private readonly int $customerId,
        private readonly User $user,
    ) {
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
