<?php

namespace App\Workshop\Bus\Query\GetWorkshopOrderById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopOrder;

class GetWorkshopOrderByIdResponse implements QueryResponse
{
    /**
     * @param WorkshopOrder $workshopOrder
     */
    public function __construct(
        public WorkshopOrder $workshopOrder
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->workshopOrder->toArray();
    }
}