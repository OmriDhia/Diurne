<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\Command;

class CreateCustomerCommand implements Command
{
    private readonly ?string $code;
    private ?string $social_reason = null;
    private ?string $tva_ce = null;
    private ?string $website = null;
    private ?int $customerGroupId = null;
    private ?int $mailingLanguageId = null;
    private ?string $firstname = null;
    private ?string $lastname = null;
    private ?string $email = null;
    private ?int $gender_id = null;
    private ?string $phone = null;
    private ?string $mobile_phone = null;
    private ?string $fax = null;
    private ?bool $is_agent = null;
    private ?bool $is_intermediary = false;
    private ?int $contactOriginId = null;
    private ?string $commentaire = null;

    public function __construct()
    {
        $this->code = 'CUST-' . strtoupper(bin2hex(random_bytes(4)));
    }

    public function isIntermediary(): ?bool
    {
        return $this->is_intermediary;
    }

    public function setIsIntermediary(?bool $is_intermediary): CreateCustomerCommand
    {
        $this->is_intermediary = $is_intermediary;

        return $this;
    }

    public function isAgent(): ?bool
    {
        return $this->is_agent;
    }

    public function setIsAgent(?bool $is_agent): CreateCustomerCommand
    {
        $this->is_agent = $is_agent;

        return $this;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): CreateCustomerCommand
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): CreateCustomerCommand
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): CreateCustomerCommand
    {
        $this->email = $email;

        return $this;
    }

    public function getGenderId(): ?int
    {
        return $this->gender_id;
    }

    public function setGenderId(?int $gender_id): CreateCustomerCommand
    {
        $this->gender_id = $gender_id;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): CreateCustomerCommand
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobile_phone;
    }

    public function setMobilePhone(?string $mobile_phone): CreateCustomerCommand
    {
        $this->mobile_phone = $mobile_phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): CreateCustomerCommand
    {
        $this->fax = $fax;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getSocialReason(): ?string
    {
        return $this->social_reason;
    }

    public function setSocialReason(?string $social_reason): CreateCustomerCommand
    {
        $this->social_reason = $social_reason;

        return $this;
    }

    public function getTvaCe(): ?string
    {
        return $this->tva_ce;
    }

    public function setTvaCe(?string $tva_ce): CreateCustomerCommand
    {
        $this->tva_ce = $tva_ce;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): CreateCustomerCommand
    {
        $this->website = $website;

        return $this;
    }

    public function getDiscountTypeId(): ?int
    {
        return $this->discountTypeId;
    }

    public function setDiscountTypeId(?int $discountTypeId): CreateCustomerCommand
    {
        $this->discountTypeId = $discountTypeId;

        return $this;
    }

    public function getCustomerGroupId(): ?int
    {
        return $this->customerGroupId;
    }

    public function setCustomerGroupId(?int $customerGroupId): CreateCustomerCommand
    {
        $this->customerGroupId = $customerGroupId;

        return $this;
    }

    public function getMailingLanguageId(): ?int
    {
        return $this->mailingLanguageId;
    }

    public function setMailingLanguageId(?int $mailingLanguageId): CreateCustomerCommand
    {
        $this->mailingLanguageId = $mailingLanguageId;

        return $this;
    }
}
