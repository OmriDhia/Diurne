<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrders;

use App\Common\Bus\Query\Query;

final class GetCarpetDesignOrdersQuery implements Query
{
    public function __construct(
        public int $currentUser,
        public ?int $page,
        public ?int $itemsPerPage,
        public ?string $orderBy,
        public ?string $orderWay,
        public ?int $designer,
        public ?string $prescripteur,
        public ?int $customer,
        public ?string $diNumber,
        public ?int $diId,
        public ?string $contremarque,
        public ?int $statusId,
        public ?bool $maquette,
        public ?bool $cmdAtelier,
        public ?int $collectionId,
        public ?int $modelId,
        public ?int $contremarqueId
    ) {}

    public function getCurrentUser(): int
    {
        return $this->currentUser;
    }

    public function getPage(): ?int
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

    public function getDesigner(): ?int
    {
        return $this->designer;
    }

    public function getPrescripteur(): ?string
    {
        return $this->prescripteur;
    }

    public function getCustomer(): ?int
    {
        return $this->customer;
    }

    public function getDiId(): ?int
    {
        return $this->diId;
    }

    public function getContremarque(): ?string
    {
        return $this->contremarque;
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function getMaquette(): ?bool
    {
        return $this->maquette;
    }

    public function getCmdAtelier(): ?bool
    {
        return $this->cmdAtelier;
    }

    public function getCollectionId(): ?int
    {
        return $this->collectionId;
    }

    public function getModelId(): ?int
    {
        return $this->modelId;
    }

    public function getDiNumber(): ?string
    {
        return $this->diNumber;
    }
}
