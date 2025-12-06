<?php

// src/Contremarque/Bus/Query/Quote/GetQuoteListQuery.php

namespace App\Contremarque\Bus\Query\GetQuoteList;

use App\Common\Bus\Query\Query;

class GetQuoteListQuery implements Query
{
    private readonly int $limit;
    private ?int $itemsPerPage = 50;

    public function __construct(private readonly int $page, ?int $itemsPerPage, private readonly ?string $orderBy, private readonly ?string $orderWay, private readonly ?string $devis, private readonly ?string $contremarque, private readonly ?int $contremarqueId, private readonly ?int $locationId, private readonly ?string $customer, private readonly ?string $commercial, private readonly ?string $creationDate, private readonly ?string $validationDate, private readonly int $userId)
    {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function getContremarque(): ?string
    {
        return $this->contremarque;
    }

    public function getContremarqueId(): int|null
    {
        return $this->contremarqueId;
    }

    public function getDevis(): ?string
    {
        return $this->devis;
    }

    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    public function getValidationDate(): ?string
    {
        return $this->validationDate;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getItemsPerPage(): int|null
    {
        return $this->itemsPerPage;
    }

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

}
