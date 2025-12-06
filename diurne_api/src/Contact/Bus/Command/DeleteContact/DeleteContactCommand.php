<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteContact;

use App\Common\Bus\Command\Command;

class DeleteContactCommand implements Command
{
    public function __construct(
        private readonly int $contactId,
    ) {
    }

    public function getContactId(): int
    {
        return $this->contactId;
    }
}
