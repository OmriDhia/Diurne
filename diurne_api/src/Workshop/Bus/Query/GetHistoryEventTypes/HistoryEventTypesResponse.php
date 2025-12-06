<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypes;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventType;

class HistoryEventTypesResponse implements QueryResponse
{
    /**
     * @param array $eventTypes
     */
    public function __construct(
        public array $eventTypes,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            fn(HistoryEventType $eventType) => $eventType->toArray(),
            $this->eventTypes
        );
    }
}