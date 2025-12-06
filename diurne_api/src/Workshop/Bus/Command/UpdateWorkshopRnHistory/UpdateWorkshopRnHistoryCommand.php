<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateWorkshopRnHistory;

use App\Common\Bus\Command\Command;

class UpdateWorkshopRnHistoryCommand implements Command
{
    /**
     * @param int $id
     * @param int|null $eventTypeId
     * @param int|null $locationId
     * @param int|null $customerId
     * @param int|null $workshopOrderId
     * @param int|null $carpetId
     * @param string|null $beginAt
     * @param string|null $endAt
     * @param string|null $updatedAt
     */
    public function __construct(
        public readonly int     $id,
        public readonly ?int    $eventTypeId = null,
        public readonly ?int    $locationId = null,
        public readonly ?int    $customerId = null,
        public readonly ?int    $workshopOrderId = null,
        public readonly ?int    $carpetId = null,
        public readonly ?string $beginAt = null,
        public readonly ?string $endAt = null,
        public readonly ?string $updatedAt = null
    )
    {
    }
}