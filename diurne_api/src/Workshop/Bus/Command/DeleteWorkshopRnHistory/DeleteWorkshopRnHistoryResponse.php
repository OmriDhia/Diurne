<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopRnHistory;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopRnHistory;

class DeleteWorkshopRnHistoryResponse implements CommandResponse
{
    /**
     * @param WorkshopRnHistory $workshopRnHistory
     */
    public function __construct(
        private readonly WorkshopRnHistory $workshopRnHistory,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->workshopRnHistory->getId(),
        ];
    }
}