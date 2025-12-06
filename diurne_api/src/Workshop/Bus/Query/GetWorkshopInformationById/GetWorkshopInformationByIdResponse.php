<?php

namespace App\Workshop\Bus\Query\GetWorkshopInformationById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopInformation;

class GetWorkshopInformationByIdResponse implements QueryResponse
{
    /**
     * @param WorkshopInformation $workshopInformation
     */
    public function __construct(
        public WorkshopInformation $workshopInformation
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->workshopInformation->toArray();
    }
}