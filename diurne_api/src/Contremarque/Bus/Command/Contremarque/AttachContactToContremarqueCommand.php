<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use App\Common\Bus\Command\Command;

class AttachContactToContremarqueCommand implements Command
{
    public function __construct(
        private readonly int $contremarqueId,
        private readonly int $contactId,
        private readonly ?bool $current = false
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

    public function getCurrent(): ?bool
    {
        return $this->current;
    }
}
