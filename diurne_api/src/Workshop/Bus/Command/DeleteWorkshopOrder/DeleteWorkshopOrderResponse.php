<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopOrder;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopOrder;

class DeleteWorkshopOrderResponse implements CommandResponse
{
    /**
     * @param int $workshopOrderId
     */
    public function __construct(
        private readonly int $workshopOrderId
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->workshopOrderId,
        ];
    }
}