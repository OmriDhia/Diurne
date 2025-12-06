<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\Command;

class CreateEventPeoplePresentCommand implements Command
{
    public function __construct(
        private readonly array $contacts,
        private readonly array $users,
        private readonly int $eventId,
    ) {
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function getEventId(): int
    {
        return $this->eventId;
    }
}
