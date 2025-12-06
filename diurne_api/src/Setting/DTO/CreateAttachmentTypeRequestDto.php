<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAttachmentTypeRequestDto
{
    public function __construct(#[Assert\NotBlank(message: 'The name field cannot be blank.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public string $name)
    {
    }
}
