<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetPrescripters;

use App\Common\Bus\Query\QueryResponse;

final class GetPrescriptersResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $prescripters,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, prescripters: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'prescripters' => $this->prescripters,
        ];
    }
}
