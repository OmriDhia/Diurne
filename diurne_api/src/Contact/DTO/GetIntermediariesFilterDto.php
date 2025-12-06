<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Common\Assert as customerAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GetIntermediariesFilterDto
{
    #[Assert\Length(max: 50, maxMessage: 'firstname cannot exceed {{ limit }} characters.')]
    #[customerAssert\Name(message: 'Please enter a valid name.')]
    public ?string $firstname = null;

    #[Assert\Length(max: 50, maxMessage: 'lastname cannot exceed {{ limit }} characters.')]
    #[customerAssert\Name(message: 'Please enter a valid name.')]
    public ?string $lastname = null;

    #[Assert\Length(max: 128, maxMessage: 'commercial cannot exceed {{ limit }} characters.')]
    #[customerAssert\Email(message: 'Please enter a valid email.')]
    public ?string $email = null;
    public ?int $intermediaryTypeId = null;
}
