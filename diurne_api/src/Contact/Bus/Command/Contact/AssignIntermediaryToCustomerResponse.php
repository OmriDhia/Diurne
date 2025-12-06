<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\CommandResponse;
use App\Contact\Entity\Customer;
use App\Contact\Entity\CustomerIntermediaryHistory;

final readonly class AssignIntermediaryToCustomerResponse implements CommandResponse
{
    public function __construct(
        public Customer $customer,
        public Customer $intermediary,
        public CustomerIntermediaryHistory $history
    ) {}

    public function toArray(): array
    {
        return [
            'customer' => [
                'id' => $this->customer->getId(),
                'name' => $this->customer->getSocialReason() ?: ($this->customer->getContactInformationSheet()?->getFirstname() . ' ' . $this->customer->getContactInformationSheet()?->getLastname()),
            ],
            'intermediary' => [
                'id' => $this->intermediary->getId(),
                'name' => $this->intermediary->getSocialReason() ?: ($this->intermediary->getContactInformationSheet()?->getFirstname() . ' ' . $this->intermediary->getContactInformationSheet()?->getLastname()),
            ],
            'relationship' => $this->history->toArray(),
        ];
    }
}
