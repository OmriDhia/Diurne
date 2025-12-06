<?php

namespace App\Workshop\Bus\Command\DeleteWorkshopInformation;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopInformation;

class WorkshopInformationResponse implements CommandResponse
{
    /**
     * @param int $workshopInformationId
     */
    public function __construct(public int $workshopInformationId)
    {
    }

    /**
     * @return WorkshopInformation[]
     */
    public function toArray(): array
    {
        return ['id' => $this->workshopInformationId];
    }
}