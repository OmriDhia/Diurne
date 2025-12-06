<?php
declare(strict_types=1);

namespace App\Contact\DTO\Origin;

use Symfony\Component\Validator\Constraints as Assert;

class CreateContactOriginRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Label cannot be empty.')]
        #[Assert\Length(
            max: 100,
            maxMessage: 'Label cannot exceed {{ limit }} characters.'
        )]
        public string $label
    )
    {
    }
}