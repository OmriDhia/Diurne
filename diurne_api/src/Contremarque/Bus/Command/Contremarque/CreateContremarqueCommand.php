<?php

namespace App\Contremarque\Bus\Command\Contremarque;

use App\Common\Bus\Command\Command;

class CreateContremarqueCommand implements Command
{
    public function __construct(
        public string $designation,
        public string $destination_location,
        public ?string $target_date,
        public int $customer_id,
        public int $customerDiscount_id,
        public ?int $prescriber_id,
        public ?float $commission,
        public ?bool $commission_on_deposit = false
    ) {
    }

    public function getDesignation(): string
    {
        return $this->designation;
    }

    public function getDestinationLocation(): string
    {
        return $this->destination_location;
    }

    public function getTargetDate(): ?string
    {
        return $this->target_date;
    }

    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    public function getCustomerDiscountId(): int
    {
        return $this->customerDiscount_id;
    }

    public function getPrescriberId(): ?int
    {
        return $this->prescriber_id;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function getCommissionOnDeposit(): ?bool
    {
        return $this->commission_on_deposit;
    }


}
