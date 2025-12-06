<?php

namespace App\Workshop\Bus\Query\GetWorkshopOrder;

use App\Common\Bus\Query\QueryResponse;

class GetWorkshopOrderQueryResponse implements QueryResponse
{
    /**
     * @param array $workshopOrder
     * @param int $total
     * @param int $pages
     */
    public function __construct(
        public array $workshopOrder,
        public int   $total,
        public int   $pages
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'data' => $this->workshopOrder,
            'meta' => [
                'total' => $this->total,
                'pages' => $this->pages
            ]
        ];
    }
}