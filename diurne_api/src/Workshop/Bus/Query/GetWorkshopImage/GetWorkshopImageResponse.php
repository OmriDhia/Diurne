<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetWorkshopImage;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\WorkshopImage;

class GetWorkshopImageResponse implements QueryResponse
{

    /**
     * @param array $workshopImage
     */
    public function __construct(public array $workshopImage)
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            fn(WorkshopImage $workshopImage) => $workshopImage->toArray(),
            $this->workshopImage
        );
    }

}