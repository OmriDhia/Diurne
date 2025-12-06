<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopInformation;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopInformation;

class UpdateWorkshopInformationResponse implements CommandResponse
{
    public function __construct(
        private readonly WorkshopInformation $workshopInformation
    )
    {
    }

    public function toArray()
    {
        return $this->workshopInformation->toArray();
    }
}