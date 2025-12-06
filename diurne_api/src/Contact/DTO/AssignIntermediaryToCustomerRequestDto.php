<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AssignIntermediaryToCustomerRequestDto
{
    public function __construct(
        #[Assert\GreaterThan(value: 0, message: "Intermediary ID must be greater than 0.")]
        public int $intermediaryId,

        #[Assert\GreaterThan(value: 0, message: "Customer ID must be greater than 0.")]
        public int $customerId,
        public ?int $intermediaryTypeId,
        public ?string $fromDate,
        public ?string $toDate = null,
    ) {}
}
