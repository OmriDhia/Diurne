<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrders;

use App\Common\Bus\Query\QueryResponse;

final class GetCarpetDesignOrdersResponse implements QueryResponse
{
    public function __construct(
        public int $count,
        public int $page,
        public int $itemsPerPage,
        public array $carpetDesignOrders,
    ) {
    }

    /**
     * @return (array|int)[]
     *
     * @psalm-return array{count: int, page: int, itemsPerPage: int, carpetDesignOrders: array}
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'carpetDesignOrders' => $this->carpetDesignOrders,
        ];
    }
}
