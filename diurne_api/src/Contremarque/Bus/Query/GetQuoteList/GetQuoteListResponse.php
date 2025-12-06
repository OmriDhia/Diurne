<?php

namespace App\Contremarque\Bus\Query\GetQuoteList;

use App\Common\Bus\Query\QueryResponse;

class GetQuoteListResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $quotes
    ) {
    }

    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'quotes' => $this->quotes,
        ];
    }
}
