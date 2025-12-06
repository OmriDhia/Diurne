<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrder;

use App\Common\Bus\Query\Query;

class GetCarpetOrderListQuery implements Query
{
    public function __construct(
        private readonly int     $page,
        private readonly ?int    $itemsPerPage,
        private readonly ?string $orderBy,
        private readonly ?string $orderWay,
        private readonly ?string $reference,
        private readonly ?string $originalQuoteReference,
        private readonly ?string $contremarque,
        private readonly ?int    $contremarqueId,
        private readonly ?string $customer,
        private readonly ?string $commercial,
        private readonly ?string $creationDate,
        private readonly int     $userId
    )
    {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getOriginalQuoteReference(): ?string
    {
        return $this->originalQuoteReference;
    }

    public function getContremarque(): ?string
    {
        return $this->contremarque;
    }

    public function getContremarqueId(): ?int
    {
        return $this->contremarqueId;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

}