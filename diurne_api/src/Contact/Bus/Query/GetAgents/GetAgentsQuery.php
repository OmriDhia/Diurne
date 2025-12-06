<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetAgents;

use App\Common\Bus\Query\Query;

final class GetAgentsQuery implements Query
{
    public ?string $orderBy = null;
    public ?string $orderWay = null;
    public ?string $firstname = null;
    public ?string $lastname = null;
    public ?string $email = null;

    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }

    public function getPage(): int|null
    {
        return $this->page;
    }

    public function getItemsPerPage(): int|null
    {
        return $this->itemsPerPage;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function setOrderBy(?string $orderBy): GetAgentsQuery
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getOrderWay(): ?string
    {
        return $this->orderWay;
    }

    public function setOrderWay(?string $orderWay): GetAgentsQuery
    {
        $this->orderWay = $orderWay;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): GetAgentsQuery
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): GetAgentsQuery
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): GetAgentsQuery
    {
        $this->email = $email;

        return $this;
    }
}
