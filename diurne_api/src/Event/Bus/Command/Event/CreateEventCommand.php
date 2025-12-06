<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\Command;
use App\Event\DTO\PeoplePresentDto;

class CreateEventCommand implements Command
{
    private int $nomenclatureId;
    private ?int $customerId = null;
    private ?int $contremarqueId = null;
    private ?int $quoteId = null;
    private ?string $next_reminder_deadline = null;
    private ?bool $disabled_reminder = null;
    private ?string $commentaire = null;
    private ?array $people_present = null;
    private ?string $event_date = null;

    public function getEventDate(): ?string
    {
        return $this->event_date;
    }

    public function setEventDate(?string $event_date): self
    {
        $this->event_date = $this->sanitizeString($event_date);
        return $this;
    }

    public function getNomenclatureId(): int
    {
        return $this->nomenclatureId;
    }

    public function setNomenclatureId(int $nomenclatureId): self
    {
        $this->nomenclatureId = $nomenclatureId;
        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getContremarqueId(): ?int
    {
        return $this->contremarqueId;
    }

    public function setContremarqueId(?int $contremarqueId): self
    {
        $this->contremarqueId = $contremarqueId;
        return $this;
    }

    public function getQuoteId(): ?int
    {
        return $this->quoteId;
    }

    public function setQuoteId(?int $quoteId): self
    {
        $this->quoteId = $quoteId;
        return $this;
    }

    public function getNextReminderDeadline(): ?string
    {
        return $this->next_reminder_deadline;
    }

    public function setNextReminderDeadline(?string $next_reminder_deadline): self
    {
        $this->next_reminder_deadline = $this->sanitizeString($next_reminder_deadline);
        return $this;
    }

    public function getDisabledReminder(): ?bool
    {
        return $this->disabled_reminder;
    }

    public function setDisabledReminder(?bool $disabled_reminder): self
    {
        $this->disabled_reminder = $disabled_reminder;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $this->sanitizeString($commentaire);
        return $this;
    }

    public function getPeoplePresent(): ?array
    {
        return $this->people_present;
    }

    public function setPeoplePresent(null|array|PeoplePresentDto $people_present): self
    {
        if ($people_present instanceof PeoplePresentDto) {
            $people_present = $people_present->toArray();
        }
        $this->people_present = $people_present;
        return $this;
    }

    private function sanitizeString(?string $value): ?string
    {
        return $value === "" ? null : $value;
    }
}
