<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Manufacturer;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Manufacturer;

class ManufacturerQueryResponse implements QueryResponse
{
    public function __construct(
        private readonly array $manufacturers,
        private readonly int $totalItems,
        private readonly int $page,
        private readonly int $itemsPerPage
    ) {
    }

    public function toArray(): array
    {
        $response = array_map(
            fn(Manufacturer $manufacturer) => $manufacturer->toArray(), 
            $this->manufacturers
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
