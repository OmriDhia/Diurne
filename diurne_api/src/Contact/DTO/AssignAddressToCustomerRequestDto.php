<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AssignAddressToCustomerRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $addressId,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $customerId,
    ) {
    }
}
