<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TarifGroup;

use App\Common\Bus\Query\QueryResponse;

class TarifGroupQueryResponse implements QueryResponse
{
    public function __construct(
        private readonly array $tarifGroups,
        private readonly int $totalItems,
        private readonly int $page,
        private readonly int $itemsPerPage
    )
    {
    }

    public function toArray(): array
    {
        $response = [];
        foreach ($this->tarifGroups as $tarifGroup) {
            $response[] = [
                'id' => $tarifGroup->getId(),
                'year' => $tarifGroup->getYear(),
            ];
        }

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
