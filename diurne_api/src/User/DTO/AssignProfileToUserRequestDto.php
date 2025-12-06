<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AssignProfileToUserRequestDto
{
    /**
     * Constructor for the class.
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.', )]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $profileId,
    ) {
    }
}
