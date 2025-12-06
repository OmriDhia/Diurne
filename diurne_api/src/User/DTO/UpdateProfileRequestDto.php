<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents the data transfer object for user registration requests.
 */
class UpdateProfileRequestDto
{
    /**
     * Constructor for the class.
     *
     * @param string $name     the name for the user
     * @param float  $discount the discount percentage for this user profile
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3, max: 60,
        )]
        public string $name,
        #[Assert\Type(type: 'float', message: 'The value {{ value }} is not a valid {{ type }}.', )]
        #[Assert\Range(
            min: 0,
            max: 100,
            notInRangeMessage: 'discount percentage must be between {{ min }}cm and {{ max }}',
        )]
        public float $discount = 0,
    ) {
    }
}
