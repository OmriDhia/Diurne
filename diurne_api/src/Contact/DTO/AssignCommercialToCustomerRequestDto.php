<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AssignCommercialToCustomerRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $commercialId,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $customerId,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'string',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public string $status,
        public string $fromDate,
        public string $toDate,
    ) {
    }
}
