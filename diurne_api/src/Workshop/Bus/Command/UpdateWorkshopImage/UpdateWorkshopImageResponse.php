<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopImage;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopImage;


class UpdateWorkshopImageResponse implements CommandResponse
{
    /**
     * @param WorkshopImage $workshopImage
     */
    public function __construct(
        private readonly WorkshopImage $workshopImage
    )
    {
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        return $this->workshopImage->toArray();
    }
}