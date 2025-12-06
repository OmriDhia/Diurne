<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Contact\DTO\AddressAsserts\Name;
use App\Common\Assert as AddressAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAddressRequestDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Full name cannot be empty.')]
        #[Assert\Length(max: 50, maxMessage: 'Full name cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public string $fullName,
        #[Assert\NotBlank(message: 'Address 1 cannot be empty.')]
        #[Assert\Length(max: 255, maxMessage: 'Address 1 cannot exceed {{ limit }} characters.')]
        #[AddressAssert\Address(message: 'Please enter a valid address. Special characters like !<>?=+@{}_$% are not allowed.')]
        public string $address1,
        #[Assert\NotBlank(message: 'City cannot be empty.')]
        #[Assert\Length(max: 64, maxMessage: 'City cannot exceed {{ limit }} characters.')]
        #[AddressAssert\CityName(message: 'Please enter a valid CityName.')]
        public string $city,
        #[Assert\NotBlank(message: 'Zip code cannot be empty.')]
        #[Assert\Length(max: 12, maxMessage: 'Zip code cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PostCode(message: 'Please enter a valid postal code.')]
        #[AddressAssert\PostCodeFormat(message: 'Please enter a valid postal code.')]
        public string $zip_code,
        #[Assert\NotBlank(message: 'Country ID cannot be empty.')]
        #[Assert\Positive(message: 'Country ID cannot be empty.')]
        public int $countryId,
        #[Assert\NotBlank(message: 'Address type ID cannot be empty.')]
        #[Assert\Positive(message: 'Address type ID cannot be empty.')]
        public int $addressTypeId,
        #[Assert\Length(max: 50, maxMessage: 'State name cannot exceed {{ limit }} characters.')]
        public ?string $state = null,
        #[Assert\Type(type: 'bool', message: 'F validity must be boolean type.')]
        public ?bool $isFValide = true,
        #[Assert\Type(type: 'bool', message: 'L validity must be boolean type.')]
        public ?bool $isLValide = true,
        #[Assert\Type(type: 'bool', message: "'isWrong' must be boolean type.")]
        public ?bool $isWrong = false,
        #[Assert\Length(max: 255, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        public ?string $comment = null,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $phone = null,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $mobile_phone = null,
    ) {
    }
}
