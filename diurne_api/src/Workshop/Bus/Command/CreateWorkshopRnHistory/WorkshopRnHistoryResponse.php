<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopRnHistory;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopRnHistory;

class WorkshopRnHistoryResponse implements CommandResponse
{
    /**
     * @param WorkshopRnHistory $workshopRnHistory
     */
    public function __construct(
        public WorkshopRnHistory $workshopRnHistory
    )
    {
    }
    
    public function toArray(): array
    {
        return $this->workshopRnHistory->toArray();
    }
}