<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\Command;

class UpdateCustomerCommand implements Command
{
    private ?string $code = null;
    private ?string $social_reason = null;
    private ?string $tva_ce = null;
    private ?string $website = null;
    private ?int $discountTypeId = null;
    private ?int $customerGroupId = null;
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

    public function __construct(
        private $customerId
    )
    {
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): UpdateCustomerCommand
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): UpdateCustomerCommand
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): UpdateCustomerCommand
    {
        $this->email = $email;

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

    public function getGenderId(): ?int
    {
        return $this->gender_id;
    }

    public function setGenderId(?int $gender_id): UpdateCustomerCommand
    {
        $this->gender_id = $gender_id;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): UpdateCustomerCommand
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobile_phone;
    }

    public function setMobilePhone(?string $mobile_phone): UpdateCustomerCommand
    {
        $this->mobile_phone = $mobile_phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): UpdateCustomerCommand
    {
        $this->fax = $fax;

        return $this;
    }

    public function isAgent(): ?bool
    {
        return $this->is_agent;
    }

    public function setIsAgent(?bool $is_agent): UpdateCustomerCommand
    {
        $this->is_agent = $is_agent;

        return $this;
    }

    public function getIsIntermediary(): ?bool
    {
        return $this->is_intermediary;
    }

    public function setIsIntermediary(?bool $is_intermediary): UpdateCustomerCommand
    {
        $this->is_intermediary = $is_intermediary;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): UpdateCustomerCommand
    {
        $this->code = $code;

        return $this;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getSocialReason(): ?string
    {
        return $this->social_reason;
    }

    public function setSocialReason(?string $social_reason): UpdateCustomerCommand
    {
        $this->social_reason = $social_reason;

        return $this;
    }

    public function getTvaCe(): ?string
    {
        return $this->tva_ce;
    }

    public function setTvaCe(?string $tva_ce): UpdateCustomerCommand
    {
        $this->tva_ce = $tva_ce;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): UpdateCustomerCommand
    {
        $this->website = $website;

        return $this;
    }

    public function getDiscountTypeId(): ?int
    {
        return $this->discountTypeId;
    }

    public function setDiscountTypeId(?int $discountTypeId): UpdateCustomerCommand
    {
        $this->discountTypeId = $discountTypeId;

        return $this;
    }

    public function getCustomerGroupId(): ?int
    {
        return $this->customerGroupId;
    }

    public function setCustomerGroupId(?int $customerGroupId): UpdateCustomerCommand
    {
        $this->customerGroupId = $customerGroupId;

        return $this;
    }
}
