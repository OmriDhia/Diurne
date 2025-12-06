<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomers;

use App\Common\Bus\Query\Query;

final readonly class GetCustomersQuery implements Query
{
    public function __construct(
        private ?int    $page,
        private ?int    $itemsPerPage,
        private ?string $firstname,
        private ?string $lastname,
        private ?string $commercial,
        private ?bool   $is_agent,
        private ?bool   $is_prescripteur,
        private ?string $prescripteur,
        private ?bool   $active,
        private ?bool   $hasInvalidCommercial,
        private ?bool   $hasOnlyOneContact,
        private ?string $socialReason,
        private ?float  $tva_ce,
        private ?string $website,
        private ?string $city,
        private ?string $zip_code,
        private ?int    $countryId,
        private ?bool   $hasWrongAddress,
        private ?bool   $hasValidAddress,
        private ?int    $mailingLanguageId,
        private ?string $contactMailing,
        private ?string $orderBy,
        private ?string $orderWay,
        private ?bool   $is_intermediary,
        private ?string $customerName,
        private ?string $exportFormat,
        private ?string $customerGroupId,
        private ?int    $currentUserId = null,
        private ?int    $commercialId = null // New property for commercialId filter
    ) {}

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function getPrescripteur(): ?string
    {
        return $this->prescripteur;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function getHasInvalidCommercial(): ?bool
    {
        return $this->hasInvalidCommercial;
    }

    public function getSocialReason(): ?string
    {
        return $this->socialReason;
    }

    public function getTvaCe(): ?float
    {
        return $this->tva_ce;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getCustomerGroups(): ?string
    {
        return $this->customerGroupId;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function getHasWrongAddress(): ?bool
    {
        return $this->hasWrongAddress;
    }

    public function getHasValidAddress(): ?bool
    {
        return $this->hasValidAddress;
    }

    public function getMailingLanguageId(): ?int
    {
        return $this->mailingLanguageId;
    }

    public function getContactMailing(): ?string
    {
        return $this->contactMailing;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getHasOnlyOneContact(): ?bool
    {
        return $this->hasOnlyOneContact;
    }

    public function IsAgent(): ?bool
    {
        return $this->is_agent;
    }

    public function IsPrescripteur(): ?bool
    {
        return $this->is_prescripteur;
    }

    public function getIsIntermediary(): ?bool
    {
        return $this->is_intermediary;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function getExportFormat(): ?string
    {
        return $this->exportFormat;
    }

    public function getCurrentUserId(): ?int
    {
        return $this->currentUserId;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }
}
