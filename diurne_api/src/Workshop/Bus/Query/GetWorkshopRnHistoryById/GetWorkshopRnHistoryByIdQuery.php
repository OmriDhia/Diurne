<?php

namespace App\Workshop\Bus\Query\GetWorkshopRnHistoryById;

use App\Common\Bus\Query\Query;

class GetWorkshopRnHistoryByIdQuery implements \App\Common\Bus\Query\Query
{
    public function __construct(
        public int $workshopRnHistoryId
    )
    {
    }

    public function getWorkshopRnHistoryId(): int
    {
        return $this->workshopRnHistoryId;
    }
}