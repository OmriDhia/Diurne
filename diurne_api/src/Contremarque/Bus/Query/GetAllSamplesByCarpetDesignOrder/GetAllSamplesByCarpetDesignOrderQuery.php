<?php

namespace App\Contremarque\Bus\Query\GetAllSamplesByCarpetDesignOrder;

use App\Common\Bus\Query\Query;

class GetAllSamplesByCarpetDesignOrderQuery implements Query
{
    public function __construct(
        private readonly int $page,
        private readonly int $itemsPerPage,
        private readonly ?string $orderBy,
        private readonly ?string $orderWay,
        private readonly int $carpetDesignOrderId
    ) {}

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
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

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }
}
