<?php

namespace App\Workshop\Bus\Command\UpdateHistoryEventType;

use App\Workshop\Entity\HistoryEventType;

class UpdateHistoryEventTypeResponse
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