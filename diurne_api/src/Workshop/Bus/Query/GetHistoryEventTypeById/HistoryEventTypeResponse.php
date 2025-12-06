<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventType;

class HistoryEventTypeResponse implements QueryResponse
{
    /**
     * @param HistoryEventType $eventType
     */
    public function __construct(
        public HistoryEventType $eventType
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->eventType->toArray();
    }
}