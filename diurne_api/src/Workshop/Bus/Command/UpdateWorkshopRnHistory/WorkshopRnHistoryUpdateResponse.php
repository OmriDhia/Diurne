<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopRnHistory;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopRnHistory;

class WorkshopRnHistoryUpdateResponse implements CommandResponse
{
    /**
     * @param WorkshopRnHistory $workshopRnHistory
     */
    public function __construct(
        public WorkshopRnHistory $workshopRnHistory
    )
    {
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {
        return $this->workshopRnHistory->toArray();
    }
}