<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteHistoryEventType;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\HistoryEventType;

class DeleteHistoryEventTypeResponse implements CommandResponse
{
    /**
     * @param int $historyEventTypeId
     */
    public function __construct(
        private readonly int $historyEventTypeId,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['id' => $this->historyEventTypeId];
    }
}