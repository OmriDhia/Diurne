<?php

declare(strict_types=1);

namespace App\Event\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateEventConfigurationRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3, max: 50,
        )]
        public string $subject,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'boolean',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public bool $is_automatic,
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int $automatic_followup_delay,
    ) {
    }
}
