<?php

namespace App\Setting\Bus\Query\DominantColor;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\DominantColor;

class GetAllDominantColorsResponse implements QueryResponse
{
    public function __construct(
        private readonly array $dominantcolors,
        private readonly int $totalItems,
        private readonly int $page,
        private readonly int $itemsPerPage
        )
    {
    }

    public function toArray(): array
    {
        $response = array_map(
            fn(DominantColor $dominantcolor) => $dominantcolor->toArray(), 
            $this->dominantcolors
        );

        if (empty($this->page)) {
            return $response;
        }

        return [
            'data' => $response,
            'pagination' => [
                'totalItems' => $this->totalItems,
                'totalPages' => ceil($this->totalItems / $this->itemsPerPage),
                'currentPage' => $this->page,
                'itemsPerPage' => $this->itemsPerPage,
            ]
        ];
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
