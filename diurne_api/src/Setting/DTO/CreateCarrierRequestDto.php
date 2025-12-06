<?php

namespace App\Setting\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCarrierRequestDto
{
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Type('string', message: 'Name must be a string')]
    #[Assert\Length(max: 50)]
    public string $name;

    #[Assert\Type('string', message: 'Contact must be a string')]
    public ?string $contact = null;

    #[Assert\Type('string', message: 'Email must be a string')]
    #[Assert\Length(max: 128)]
    public ?string $email = null;

    #[Assert\Type('string', message: 'Phone must be a string')]
    #[Assert\Length(max: 30)]
    public ?string $phone = null;

    #[Assert\Type('string', message: 'Fax must be a string')]
    #[Assert\Length(max: 30)]
    public ?string $fax = null;

    #[Assert\Type('string', message: 'Address must be a string')]
    public ?string $address = null;
}
