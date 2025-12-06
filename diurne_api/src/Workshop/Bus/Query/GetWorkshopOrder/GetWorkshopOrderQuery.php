<?php

namespace App\Workshop\Bus\Query\GetWorkshopOrder;

use App\Common\Bus\Query\Query;

class GetWorkshopOrderQuery implements Query
{

    public function __construct(
        public ?int             $id = null,
        public ?int             $page = 1,
        public ?int             $itemsPerPage = 10,
        public ?array           $filters = null,
        public ?array           $orderBy = null,
        public readonly ?int    $currentUserId = null,
        public readonly ?string $customer = null,
        public readonly ?string $contremarque = null,
        public readonly ?string $reference = null,
        public readonly ?string $commercial = null,
        public readonly ?string $collection = null,
        public readonly ?string $model = null,
        public readonly ?string $rn = null,
        public readonly ?string $location = null,
        public readonly ?int    $statusId = null,
        public readonly ?int    $designerId = null,
        // Sorting
        public readonly ?string $orderWay = 'DESC'
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }

    public function getOrderBy(): ?array
    {
        return $this->orderBy;
    }

    public function getCurrentUserId(): ?int
    {
        return $this->currentUserId;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function getContremarque(): ?string
    {
        return $this->contremarque;
    }


    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function getDesignerId(): ?int
    {
        return $this->designerId;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getRn(): ?string
    {
        return $this->rn;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

}