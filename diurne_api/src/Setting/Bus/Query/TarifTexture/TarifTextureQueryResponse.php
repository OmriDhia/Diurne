<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifTexture;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TarifTexture;

class TarifTextureQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|TarifTexture> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly int   $totalItems,
        private readonly ?int  $page,
        private readonly ?int  $itemsPerPage
    )
    {
    }

    public function toArray(): array
    {
        $formatted = [];
        foreach ($this->items as $it) {
            if ($it instanceof TarifTexture) {
                $formatted[] = $it->toArray();
            } else {
                $formatted[] = $it;
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

