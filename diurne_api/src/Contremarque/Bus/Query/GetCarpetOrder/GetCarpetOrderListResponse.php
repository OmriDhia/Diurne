<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrder;

use App\Common\Bus\Query\QueryResponse;

class GetCarpetOrderListResponse implements QueryResponse
{
    /**
     * @param int $count
     * @param int $page
     * @param int $itemsPerPage
     * @param array $carpetOrders
     */
    public function __construct(
        public int   $count,
        public int   $page,
        public int   $itemsPerPage,
        public array $carpetOrders
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'carpetOrders' => $this->carpetOrders,
        ];
    }
}