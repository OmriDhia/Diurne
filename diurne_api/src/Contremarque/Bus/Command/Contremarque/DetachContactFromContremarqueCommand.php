<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use App\Common\Bus\Command\Command;

class DetachContactFromContremarqueCommand implements Command
{
    public function __construct(
        private readonly int $contremarqueId,
        private readonly int $contactId
    ) {
    }

    public function getContremarqueId(): int
    {
        return $this->contremarqueId;
    }

    public function getContactId(): int
    {
        return $this->contactId;
    }
}
