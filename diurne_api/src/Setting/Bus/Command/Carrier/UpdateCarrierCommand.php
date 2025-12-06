<?php

namespace App\Setting\Bus\Command\Carrier;

use App\Common\Bus\Command\Command;

class UpdateCarrierCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $contact,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $fax,
        public readonly string $address,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
