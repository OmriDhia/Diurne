<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\CommandResponse;

final class UpdateContactResponse implements CommandResponse
{
    public function __construct(
        public int     $id,
        public int     $user_id,
        public string  $firstName,
        public string  $lastName,
        public string  $email,
        public ?int    $gender_id,
        public ?bool   $mailing,
        public ?bool   $mailing_with_calligraphie,
        public ?string $phone,
        public ?string $mobile_phone,
        public ?string $fax,
        public ?int    $customerId,
    )
    {
    }

    /**
     * @return (bool|int|null|string)[]
     *
     * @psalm-return array{contact_id: int, user_id: int, customer_id: int|null, firstname: string, lastname: string, email: string, gender_id: int|null, mailing: bool|null, mailing_with_calligraphie: bool|null, phone: null|string, mobile_phone: null|string, fax: null|string}
     */
    public function toArray(): array
    {
        return [
            'contact_id' => $this->id,
            'user_id' => $this->user_id,
            'customer_id' => $this->customerId,
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'email' => $this->email,
            'gender_id' => $this->gender_id,
            'mailing' => $this->mailing,
            'mailing_with_calligraphie' => $this->mailing_with_calligraphie,
            'phone' => $this->phone,
            'mobile_phone' => $this->mobile_phone,
            'fax' => $this->fax,
        ];
    }
}
