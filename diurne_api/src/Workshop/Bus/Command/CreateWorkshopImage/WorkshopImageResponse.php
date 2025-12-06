<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopImage;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopImage;


class WorkshopImageResponse implements CommandResponse
{
    /**
     * @param WorkshopImage $workshopImageId
     */
    public function __construct(
        public WorkshopImage $workshopImageId
    )
    {
    }

    /**
     * @return WorkshopImage[]
     */
    public function toArray()
    {

        return $this->workshopImageId->toArray();

    }
}