<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetImagesByCarpetDesignOrderId;

use App\Common\Bus\Query\Query;

final readonly class GetImagesByCarpetDesignOrderIdQuery implements Query
{
    public function __construct(
        private int $carpetDesignOrderId,
        private ?int $page = null,
        private ?int $itemsPerPage = null,
        private bool $forceRefresh = false
    ) {}

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function isForceRefresh(): bool
    {
        return $this->forceRefresh;
    }
}
