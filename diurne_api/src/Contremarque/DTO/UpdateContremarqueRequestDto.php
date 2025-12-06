<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

class UpdateContremarqueRequestDto
{
    public string $designation;
    public string $destination_location;
    public ?string $target_date = null;
    public int $customer_id;
    public int $customerDiscount_id;
    public ?int $prescriber_id = null;
    public ?float $commission = null;
    public ?bool $commission_on_deposit = null;
}
