<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopOrder;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopOrder;

class UpdateWorkshopOrderResponse implements CommandResponse
{

    public function __construct(
        private readonly WorkshopOrder $workshopOrder
    )
    {
    }

    public function toArray(): array
    {
        return $this->workshopOrder->toArray();
    }
}