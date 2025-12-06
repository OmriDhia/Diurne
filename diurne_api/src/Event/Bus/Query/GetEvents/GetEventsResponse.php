<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEvents;

use App\Common\Bus\Query\QueryResponse;

final class GetEventsResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $events,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, events: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'events' => $this->events,
        ];
    }
}
