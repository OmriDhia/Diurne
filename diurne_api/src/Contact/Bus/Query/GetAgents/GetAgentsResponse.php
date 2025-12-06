<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetAgents;

use App\Common\Bus\Query\QueryResponse;

final class GetAgentsResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $agents,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, agents: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'agents' => $this->agents,
        ];
    }
}
