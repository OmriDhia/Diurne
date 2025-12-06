<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use App\Contact\DTO\AddressAssert\PhoneNumber;
use App\Common\Assert as AddressAsserts;
use App\Common\Assert\IsLastnameRequired;
use App\Common\Assert\IsSocialReasonRequired;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerRequestDto extends BaseDto
{
    public function __construct(
        #[IsSocialReasonRequired]
        #[Assert\Type(type: 'string')]
        public ?string $social_reason,
        public ?string $tva_ce,
        #[AddressAsserts\Url(message: 'Please enter a valid name.')]
        public ?string $website,
        public ?int    $mailing_lang_id,
        #[Assert\NotBlank(message: 'Discount type cannot be empty.')]
        #[Assert\Positive(message: 'Discount type cannot be empty.')]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int    $discountTypeId,
        #[Assert\NotBlank(message: 'Customer group cannot be empty.')]
        #[Assert\Positive(message: 'Customer group cannot be empty.')]
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public int    $customerGroupId,
        #[Assert\Type(
            type: 'integer',
            message: 'The value {{ value }} is not a valid {{ type }}.',
        )]
        public ?int    $mailingLanguageId,

        #[Assert\Length(max: 50, maxMessage: 'Firstname cannot exceed {{ limit }} characters.')]
        #[AddressAsserts\Name(message: 'Please enter a valid name.')]
        public ?string $firstname,
        #[IsLastnameRequired]
        public ?string $lastname,
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.',)]
        public ?string $email,
        public ?int    $gender_id,
        #[Assert\NotNull(message: 'Contact origin ID is required.')]
        #[Assert\Positive(message: 'Contact origin ID is required.')]
        public int $contact_origin_id,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $phone = null,
        #[Assert\Length(max: 15, maxMessage: 'Comment cannot exceed {{ limit }} characters.')]
        #[PhoneNumber(message: 'Please enter a valid phone Number.')]
        public ?string $mobile_phone = null,
        public ?string $fax = null,
        public ?bool   $is_agent = false,
        public ?bool   $is_intermediary = false,
        public ?string $commentaire = null,
    ) {}
}
