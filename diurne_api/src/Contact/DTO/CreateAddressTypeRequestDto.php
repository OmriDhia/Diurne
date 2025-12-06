<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAddressTypeRequestDto
{
    /**
     * Constructor for the class.
     *
     * @param string $name the name for the contact group
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3, max: 128,
        )]
        public string $name,
    ) {
    }
}
