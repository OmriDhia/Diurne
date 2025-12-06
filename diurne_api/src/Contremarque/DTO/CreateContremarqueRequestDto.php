<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateContremarqueRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Designation cannot be empty.')]
        #[Assert\Length(max: 255, maxMessage: 'Lastname cannot exceed {{ limit }} characters.')]
        public string $designation,
        public string $destination_location,
        public ?string $target_date,
        public int $customer_id,
        public int $customerDiscount_id,
        public ?int $prescriber_id,
        public ?float $commission,
        #[Assert\Type(type: 'bool', message: 'commission_on_deposit must be boolean type.')]
        public ?bool $commission_on_deposit = false
    ) {
    }
}
