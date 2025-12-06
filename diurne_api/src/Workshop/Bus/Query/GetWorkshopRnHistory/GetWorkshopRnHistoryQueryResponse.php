<?php

namespace App\Workshop\Bus\Query\GetWorkshopRnHistory;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopRnHistory;

class GetWorkshopRnHistoryQueryResponse implements QueryResponse
{
    /**
     * @param array $workshopRnHistory
     */
    public function __construct(
        public array $workshopRnHistory,

    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            fn(WorkshopRnHistory $workshopRnHistory) => $workshopRnHistory->toArray(),
            $this->workshopRnHistory
        );
    }
}