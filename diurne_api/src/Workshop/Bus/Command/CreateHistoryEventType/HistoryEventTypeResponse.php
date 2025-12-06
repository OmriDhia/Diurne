<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventType;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\HistoryEventType;

class HistoryEventTypeResponse implements CommandResponse
{
    /**
     * @param HistoryEventType $eventType
     */
    public function __construct(
        private readonly HistoryEventType $eventType
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->eventType->getId(),
            'name' => $this->eventType->getName(),
        ];
    }
}