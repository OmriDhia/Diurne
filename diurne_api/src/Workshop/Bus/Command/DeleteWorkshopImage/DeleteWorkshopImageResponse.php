<?php

namespace App\Workshop\Bus\Command\DeleteWorkshopImage;

use App\Workshop\Entity\WorkshopImage;

class DeleteWorkshopImageResponse
{
    /**
     * @param int $workshopImageId
     */
    public function __construct(
        private readonly int $workshopImageId,
    )
    {
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        return [
            'id' => $this->workshopImageId
        ];
    }
}