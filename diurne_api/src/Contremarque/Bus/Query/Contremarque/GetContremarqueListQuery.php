<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\Contremarque;

use App\Common\Bus\Query\Query;

class GetContremarqueListQuery implements Query
{
    public function __construct(public ?int $page, public ?int $itemsPerPage, public ?string $orderWay, public ?string $designation, public ?int $customerId, public ?int $contremarqueId, public ?int $commercialId, public ?string $creationDate, public ?string $targetDate, public ?int $limit, public ?string $order, public ?string $customerName, public ?string $commercial, public ?string $prescripteur, public ?bool $relaunchExceeded, public ?bool $relaunchExceededByWeek, public ?bool $withoutRelaunch, public ?bool $isCurrentProject, public ?int $currentUser)
    {
    }

    public function getContremarqueId(): ?int
    {
        return $this->contremarqueId;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function getCommercialId(): ?int
    {
        return $this->commercialId;
    }

    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    public function getTargetDate(): ?string
    {
        return $this->targetDate;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function getPrescripteur(): ?string
    {
        return $this->prescripteur;
    }

    public function getRelaunchExceeded(): ?bool
    {
        return $this->relaunchExceeded;
    }

    public function getRelaunchExceededByWeek(): ?bool
    {
        return $this->relaunchExceededByWeek;
    }

    public function getIsCurrentProject(): ?bool
    {
        return $this->isCurrentProject;
    }

    public function getWithoutRelaunch(): ?bool
    {
        return $this->withoutRelaunch;
    }

    public function getCurrentUser(): int|null
    {
        return $this->currentUser;
    }
}
