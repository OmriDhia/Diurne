<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopInformation;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopInformation;


class WorkshopInformationResponse implements CommandResponse
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