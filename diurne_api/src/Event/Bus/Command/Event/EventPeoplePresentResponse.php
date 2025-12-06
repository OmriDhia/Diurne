<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use DateTimeInterface;
use App\Common\Bus\Command\CommandResponse;
use App\Event\Entity\Event;

final readonly class EventPeoplePresentResponse implements CommandResponse
{
    public function __construct(
        private Event $event
    ) {
    }

    /**
     * @return (DateTimeInterface|array|bool|int|mixed|null|string)[]
     *
     * @psalm-return array{event_id: int|null, nomenclature: array<never, never>|mixed, next_reminder_deadline: DateTimeInterface|null, reminder_disabled: bool|null, commentaire: null|string, people_present: ''|array|null, event_date: DateTimeInterface|null}
     */
    public function toArray(): array
    {
        return $this->event->toArray();
    }
}
