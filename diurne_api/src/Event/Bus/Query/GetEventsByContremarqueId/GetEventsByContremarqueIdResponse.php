<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEventsByContremarqueId;

use App\Common\Bus\Query\QueryResponse;

final class GetEventsByContremarqueIdResponse implements QueryResponse
{
    public function __construct(
        public array $eventData
    ) {}

    /**
     * @return array[]
     *
     * @psalm-return array{events: array}
     */
    public function toArray(): array
    {
        return [
            'events' => $this->eventData,
        ];
    }
}
