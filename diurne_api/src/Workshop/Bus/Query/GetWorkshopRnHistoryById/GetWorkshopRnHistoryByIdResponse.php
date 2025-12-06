<?php

namespace App\Workshop\Bus\Query\GetWorkshopRnHistoryById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopRnHistory;

class GetWorkshopRnHistoryByIdResponse implements QueryResponse
{
    /**
     * @param WorkshopRnHistory $workshopRnHistory
     */
    public function __construct(
        public WorkshopRnHistory $workshopRnHistory
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->workshopRnHistory->toArray();
    }
}