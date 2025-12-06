<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetUnitOfMeasurements;

use App\Common\Bus\Query\Query;

final readonly class GetUnitOfMeasurementsQuery implements Query
{
    public function __construct(
        public string $feetInchCombinated,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
        public bool $forceRefresh = false
    ) {}

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
