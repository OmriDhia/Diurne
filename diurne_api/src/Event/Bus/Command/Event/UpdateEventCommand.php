<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\Command;

class UpdateEventCommand implements Command
{
    private int $eventId;
    private ?int $nomenclatureId = null;
    private ?int $customerId = null;
    private ?int $contremarqueId = null;
    private ?int $quoteId = null;
    private ?string $next_reminder_deadline = null;
    private ?bool $disabled_reminder = null;
    private ?string $commentaire = null;
    private ?array $people_present = null;
    private ?string $event_date = null;

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): static
    {
        $this->eventId = $eventId;
        return $this;
    }

    public function getNomenclatureId(): ?int
    {
        return $this->nomenclatureId;
    }

    public function setNomenclatureId(?int $nomenclatureId): static
    {
        $this->nomenclatureId = $nomenclatureId;
        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): static
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getContremarqueId(): ?int
    {
        return $this->contremarqueId;
    }

    public function setContremarqueId(?int $contremarqueId): static
    {
        $this->contremarqueId = $contremarqueId;
        return $this;
    }

    public function getQuoteId(): ?int
    {
        return $this->quoteId;
    }

    public function setQuoteId(?int $quoteId): static
    {
        $this->quoteId = $quoteId;
        return $this;
    }

    public function getNextReminderDeadline(): ?string
    {
        return $this->next_reminder_deadline;
    }

    public function setNextReminderDeadline(?string $next_reminder_deadline): static
    {
        $this->next_reminder_deadline = $next_reminder_deadline;
        return $this;
    }

    public function getDisabledReminder(): ?bool
    {
        return $this->disabled_reminder;
    }

    public function setDisabledReminder(?bool $disabled_reminder): static
    {
        $this->disabled_reminder = $disabled_reminder;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getPeoplePresent(): ?array
    {
        return $this->people_present;
    }

    public function setPeoplePresent(?array $people_present): static
    {
        // Ensure the data is properly structured as expected by the application
        if (isset($people_present['contacts']) && isset($people_present['users'])) {
            $this->people_present = [
                'contacts' => $people_present['contacts'],
                'users' => $people_present['users'],
            ];
        } else {
            $this->people_present = null;
        }

        return $this;
    }

    public function getEventDate(): ?string
    {
        return $this->event_date;
    }

    public function setEventDate(?string $event_date): static
    {
        $this->event_date = $event_date;
        return $this;
    }
}
