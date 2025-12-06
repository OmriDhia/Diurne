<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Address;

use InvalidArgumentException;
use App\Common\Bus\Command\Command;

final readonly class UpdateAddressCommand implements Command
{
    public function __construct(
        public int $addressId,
        public string $fullName,
        public string $address1,
        public string $city,
        public string $zipCode,
        public string $state,
        public int $countryId,
        public int $addressTypeId,
        public ?bool $isFValide = null,
        public ?bool $isLValide = null,
        public ?bool $isWrong = null,
        public ?string $comment = null,
        public ?string $phone = null,
        public ?string $mobilePhone = null,
    ) {
        if ($addressId <= 0) {
            throw new InvalidArgumentException('Address ID must be greater than 0.');
        }
        if (empty($fullName)) {
            throw new InvalidArgumentException('Full name cannot be empty.');
        }
        if (empty($address1)) {
            throw new InvalidArgumentException('Address1 cannot be empty.');
        }
        if (empty($city)) {
            throw new InvalidArgumentException('City cannot be empty.');
        }
        if (empty($zipCode)) {
            throw new InvalidArgumentException('Zip code cannot be empty.');
        }

        if ($countryId <= 0) {
            throw new InvalidArgumentException('Country ID must be greater than 0.');
        }
        if ($addressTypeId <= 0) {
            throw new InvalidArgumentException('Address type ID must be greater than 0.');
        }
    }
}
