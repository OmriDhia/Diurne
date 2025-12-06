<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopOrder;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\WorkshopOrder;

class CreateWorkshopOrderResponse implements CommandResponse
{

    /**
     * @param WorkshopOrder[] $workshopOrders
     */
    public function __construct(
        private readonly array $workshopOrders
    )
    {
    }

    public function toArray(): array
    {
        return array_map(
            static fn(WorkshopOrder $workshopOrder): array => $workshopOrder->toArray(),
            $this->workshopOrders
        );
    }
}
