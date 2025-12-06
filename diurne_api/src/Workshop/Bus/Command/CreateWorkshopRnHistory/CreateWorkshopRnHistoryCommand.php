<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateWorkshopRnHistory;

use App\Common\Bus\Command\Command;


class CreateWorkshopRnHistoryCommand implements Command
{
    /**
     * @param int $eventTypeId
     * @param int $locationId
     * @param int $customerId
     * @param int $workshopOrderId
     * @param string $beginAt
     * @param string $endAt
     * @param int|null $carpetId
     * @param string |null $createdAt
     * @param string |null $updatedAt
     */
    public function __construct(
        public readonly int     $eventTypeId,
        public readonly int     $locationId,
        public readonly int     $customerId,
        public readonly int     $workshopOrderId,
        public readonly string  $beginAt,
        public readonly string  $endAt,
        public readonly ?int    $carpetId = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null
    )
    {
    }
}