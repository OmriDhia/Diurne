<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEvents;

use App\Common\Bus\Query\Query;

final readonly class GetEventsQuery implements Query
{
    public function __construct(
        private ?int $page,
        private ?int $itemsPerPage,
        private ?string $firstname,
        private ?string $lastname,
        private ?string $email,
        private ?float $tvaCe,
        private ?string $website,
        private ?string $contact,
        private ?string $prescripteur,
        private ?string $socialReason,
        private ?string $commercial,
        private ?bool $active,
        private ?bool $hasInvalidCommercial,
        private ?bool $hasOnlyOneContact,
        private ?bool $onlyLastEvent,
        private ?bool $hasNoProject,
        private ?bool $hasNextStep,
        private ?string $eventDate_from,
        private ?string $eventDate_to,
        private ?string $next_reminder_deadline_from,
        private ?string $next_reminder_deadline_to,
        private ?string $subject,
        private ?string $customerGroups,
        private ?int $nomenclatureId,
        private ?int $contremarqueId,
        private ?int $customerId,
        private ?int $quoteId,
        private ?string $orderBy,
        private ?string $orderWay
    ) {
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getSocialReason(): ?string
    {
        return $this->socialReason;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getHasInvalidCommercial(): ?bool
    {
        return $this->hasInvalidCommercial;
    }

    public function getHasOnlyOneContact(): ?bool
    {
        return $this->hasOnlyOneContact;
    }

    public function getHasNoProject(): ?bool
    {
        return $this->hasNoProject;
    }

    public function getHasNextStep(): ?bool
    {
        return $this->hasNextStep;
    }

    public function getEventDateFrom(): ?string
    {
        return $this->eventDate_from;
    }

    public function getEventDateTo(): ?string
    {
        return $this->eventDate_to;
    }

    public function getNextReminderDeadlineFrom(): ?string
    {
        return $this->next_reminder_deadline_from;
    }

    public function getNextReminderDeadlineTo(): ?string
    {
        return $this->next_reminder_deadline_to;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return null|string
     */
    public function getCustomerGroups(): string|null
    {
        return $this->customerGroups;
    }

    public function getNomenclatureId(): ?int
    {
        return $this->nomenclatureId;
    }

    public function getOnlyLastEvent(): ?bool
    {
        return $this->onlyLastEvent;
    }

    public function getTvaCe(): ?float
    {
        return $this->tvaCe;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function getPrescripteur(): ?string
    {
        return $this->prescripteur;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getContremarqueId(): int|null
    {
        return $this->contremarqueId;
    }

    public function getQuoteId(): int|null
    {
        return $this->quoteId;
    }

    public function getCustomerId(): int|null
    {
        return $this->customerId;
    }
}
