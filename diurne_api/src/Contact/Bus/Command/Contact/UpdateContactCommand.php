<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\Command;

class UpdateContactCommand implements Command
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private ?int $gender_id = null;
    private ?bool $mailing = null;
    private ?bool $mailing_with_calligraphie = null;
    private ?string $phone = null;
    private ?string $mobile_phone = null;
    private ?string $fax = null;
    private ?int $contactOriginId = null;
    private ?string $commentaire = null;

    public function __construct(
        private $customerId,
        private $contactId,
    )
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstname(string $firstName): UpdateContactCommand
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): UpdateContactCommand
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UpdateContactCommand
    {
        $this->email = $email;

        return $this;
    }

    public function getGenderId(): ?int
    {
        return $this->gender_id;
    }

    public function setGenderId(?int $gender_id): UpdateContactCommand
    {
        $this->gender_id = $gender_id;

        return $this;
    }

    public function getMailingWithCalligraphie(): ?bool
    {
        return $this->mailing_with_calligraphie;
    }

    public function setMailingWithCalligraphie(?bool $mailing_with_calligraphie): UpdateContactCommand
    {
        $this->mailing_with_calligraphie = $mailing_with_calligraphie;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): UpdateContactCommand
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobile_phone;
    }

    public function setMobilePhone(?string $mobile_phone): UpdateContactCommand
    {
        $this->mobile_phone = $mobile_phone;

        return $this;
    }

    public function getMailing(): ?bool
    {
        return $this->mailing;
    }

    public function setMailing(?bool $mailing): UpdateContactCommand
    {
        $this->mailing = $mailing;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): UpdateContactCommand
    {
        $this->fax = $fax;

        return $this;
    }

    public function getContactId()
    {
        return $this->contactId;
    }

    public function getContactOriginId(): ?int
    {
        return $this->contactOriginId;
    }

    public function setContactOriginId(?int $id): self
    {
        $this->contactOriginId = $id;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }
}
