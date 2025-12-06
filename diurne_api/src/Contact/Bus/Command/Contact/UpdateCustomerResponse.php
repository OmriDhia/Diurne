<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\CommandResponse;

final class UpdateCustomerResponse implements CommandResponse
{
    public function __construct(
        public int     $id,
        public ?string $code,
        public ?string $social_reason,
        public ?string $tva_ce,
        public ?string $website,
        public ?string $firstname,
        public ?string $lastname,
        public ?string $email,
        public ?string $phone,
        public ?string $mobile_phone,
        public ?bool   $is_intermediary,
        public ?string $intermediaryType,
        public ?int    $discountTypeId,
        public ?int    $customerGroupId,
        public ?int    $mailingLanguageId,
        public ?int    $contact_origin_id,
        public ?string $commentaire,
    )
    {
    }

    /**
     * @return (bool|int|null|string)[]
     *
     * @psalm-return array{customer_id: int, code: null|string, social_reason: null|string, tva_ce: null|string, website: null|string, firstname: null|string, lastname: null|string, email: null|string, phone: null|string, mobile_phone: null|string, is_intermediary: bool|null, intermediary_type: null|string, discountTypeId: int|null, customerGroupId: int|null, mailingLanguageId: int|null, contact_origin_id: int|null, commentaire: null|string}
     */
    public function toArray(): array
    {
        return [
            'customer_id' => $this->id,
            'code' => $this->code,
            'social_reason' => $this->social_reason,
            'tva_ce' => $this->tva_ce,
            'website' => $this->website,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile_phone' => $this->mobile_phone,
            'is_intermediary' => $this->is_intermediary,
            'intermediary_type' => $this->intermediaryType,
            'discountTypeId' => $this->discountTypeId,
            'customerGroupId' => $this->customerGroupId,
            'mailingLanguageId' => $this->mailingLanguageId,
            'contact_origin_id' => $this->contact_origin_id,
            'commentaire' => $this->commentaire,
        ];
    }
}
