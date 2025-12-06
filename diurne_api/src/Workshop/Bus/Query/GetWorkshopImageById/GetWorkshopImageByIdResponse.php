<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImageById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopImage;

class GetWorkshopImageByIdResponse implements QueryResponse
{

    /**
     * @param WorkshopImage $WorkshopImageId
     */
    public function __construct(public WorkshopImage $WorkshopImageId)
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->WorkshopImageId->toArray();
    }

}