<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Contact\DTO\AddressAsserts\Name;
use App\Common\Assert as AddressAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateContactRequestDto
{
    public function __construct(
        #[Assert\Length(max: 50, maxMessage: 'Firstname cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public string  $firstname,
        #[Assert\NotBlank(message: 'Lastname cannot be empty.')]
        #[Assert\Length(max: 50, maxMessage: 'Lastname cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public string  $lastname,
        #[Assert\NotBlank]
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.',)]
        public string  $email,
        public ?int    $gender_id,
        public ?bool   $mailing,
        #[Assert\Type(type: 'bool', message: 'mailing_with_calligraphie must be boolean type.')]
        public ?bool   $mailing_with_calligraphie = false,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $phone = null,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $mobile_phone = null,
        public ?string $fax = null,
    ) {}
}
