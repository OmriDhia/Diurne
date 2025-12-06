<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\MaterialPrice;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\MaterialPrice;

class MaterialPriceQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|MaterialPrice> $materialPrices
     */
    public function __construct(
        private readonly array $materialPrices,
        private readonly int   $totalItems,
        private readonly ?int  $page,
        private readonly ?int  $itemsPerPage
    )
    {
    }

    public function toArray(): array
    {
        $formatted = [];
        foreach ($this->materialPrices as $mp) {
            if ($mp instanceof MaterialPrice) {
                $formatted[] = $mp->toArray();
            } else {
                // already an array (from cache)
                $formatted[] = $mp;
            }
        }

        return [
            'data' => $formatted,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage ?? count($formatted),
            ],
        ];
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }
}