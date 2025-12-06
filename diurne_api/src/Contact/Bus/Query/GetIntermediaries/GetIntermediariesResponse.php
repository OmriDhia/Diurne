<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetIntermediaries;

use App\Common\Bus\Query\QueryResponse;

final class GetIntermediariesResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $intermediaries,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, intermediaries: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'intermediaries' => $this->intermediaries,
        ];
    }
}
