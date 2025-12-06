<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Contact\DTO\AddressAsserts\Url;
use App\Contact\DTO\AddressAsserts\Name;
use App\Common\Assert as AddressAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCustomerRequestDto
{
    public function __construct(
        public ?string $social_reason,
        public ?string $tva_ce,
        #[Url(message: 'Please enter a valid name.')]
        public ?string $website,
        public ?int    $mailing_lang_id,
        public ?int    $discountTypeId,
        public ?int    $customerGroupId,
        #[Assert\Length(max: 50, maxMessage: 'Firstname cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public ?string $firstname,
        #[Assert\Length(max: 50, maxMessage: 'Lastname cannot exceed {{ limit }} characters.')]
        #[Name(message: 'Please enter a valid name.')]
        public ?string $lastname,
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.',)]
        public ?string $email,
        public ?int    $gender_id,
        public ?bool   $mailing,
        public ?bool   $is_agent,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $phone = null,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[AddressAssert\PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $mobile_phone = null,
        public ?string $fax = null,
        public ?bool   $is_intermediary = false,
        #[Assert\NotNull(message: 'Contact origin ID is required.')]
        public ?int    $contact_origin_id = null,
        public ?string $commentaire = null,
    )
    {
    }
}
