<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Address;

use App\Common\Bus\Command\CommandResponse;
use App\Contact\Entity\AddressType;
use App\Setting\Entity\Country;

final readonly class AddressResponse implements CommandResponse
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $address1,
        public string $city,
        public AddressType $addressType,
        public Country $country,
        public string $zipCode,
        public ?string $state = null,
        public bool $isFValide = true,
        public bool $isLValide = true,
        public bool $isWrong = false,
        public ?string $comment = null,
        public ?string $phone = null,
        public ?string $mobilePhone = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'address1' => $this->address1,
            'city' => $this->city,
            'state' => $this->state,
            'zipCode' => $this->zipCode,
            'isFValide' => $this->isFValide,
            'isLValide' => $this->isLValide,
            'isWrong' => $this->isWrong,
            'comment' => $this->comment,
            'phone' => $this->phone,
            'mobilePhone' => $this->mobilePhone,
            'addressType' => $this->serializeAddressType(),
            'country' => $this->serializeCountry(),
        ];
    }

    private function serializeAddressType(): array
    {
        if (!$this->addressType instanceof AddressType) {
            return [];
        }

        return [
            'id' => $this->addressType->getId(),
            'name' => $this->addressType->getName(),
            // Add more fields as needed
        ];
    }

    private function serializeCountry(): array
    {
        if (!$this->country instanceof Country) {
            return [];
        }

        return [
            'id' => $this->country->getId(),
            'name' => $this->country->getName(),
            // Add more fields as needed
        ];
    }
}
