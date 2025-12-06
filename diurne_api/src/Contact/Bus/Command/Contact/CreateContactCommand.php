<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\Command;

class CreateContactCommand implements Command
{
    private ?int $genderId = null;
    private ?bool $mailing = null;
    private ?bool $mailingWithCalligraphie = null;
    private ?string $phone = null;
    private ?string $mobilePhone = null;
    private ?string $fax = null;
    private string $firstName;
    private string $lastName;
    private ?string $email = null;

    public function __construct(
        private readonly string|int $customerId
    )
    {
    }


    public function getCustomerId(): string|int
    {
        return $this->customerId;
    }

    public function getGenderId(): ?int
    {
        return $this->genderId;
    }

    public function setGenderId(?int $genderId): self
    {
        $this->genderId = $genderId;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstname(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMailing(): ?bool
    {
        return $this->mailing;
    }

    public function setMailing(?bool $mailing): self
    {
        $this->mailing = $mailing;
        return $this;
    }


    public function getMailingWithCalligraphie(): ?bool
    {
        return $this->mailingWithCalligraphie;
    }

    public function setMailingWithCalligraphie(?bool $mailingWithCalligraphie): self
    {
        $this->mailingWithCalligraphie = $mailingWithCalligraphie;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(?string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;
        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;
        return $this;
    }


}
