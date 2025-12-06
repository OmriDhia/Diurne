<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Common\Assert as AddressAssert;
use Symfony\Component\Validator\Constraints as Assert;
use App\Common\DTO\BaseDto;

class CreateContactRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Length(
            max: 50,
            maxMessage: 'Firstname cannot exceed {{ limit }} characters.'
        )]
        #[AddressAssert\Name(message: 'Please enter a valid name.')]
        public string  $firstname,

        #[Assert\NotBlank(message: 'Lastname cannot be empty.')]
        #[Assert\Length(
            max: 50,
            maxMessage: 'Lastname cannot exceed {{ limit }} characters.'
        )]
        #[AddressAssert\Name(message: 'Please enter a valid name.')]
        public string  $lastname,

        public ?string $email,

        #[Assert\NotBlank(message: 'Gender id is required')]
        #[Assert\Positive(message: 'Gender id must be greater than zero.')]
        public int $gender_id,

        public ?bool   $mailing,

        #[Assert\Type(
            type: 'bool',
            message: 'mailing_with_calligraphie must be boolean type.'
        )]
        public ?bool   $mailing_with_calligraphie = false,

        #[Assert\Length(
            max: 15,
            maxMessage: 'Phone cannot exceed {{ limit }} characters.'
        )]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $phone = null,

        #[Assert\Length(
            max: 15,
            maxMessage: 'Mobile phone cannot exceed {{ limit }} characters.'
        )]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $mobile_phone = null,

        public ?string $fax = null,


    ) {}
}
