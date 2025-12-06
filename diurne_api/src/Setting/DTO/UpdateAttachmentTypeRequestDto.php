<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateAttachmentTypeRequestDto
{
    public function __construct(#[Assert\Length(
        max: 255,
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public ?string $name = null)
    {
    }
}
