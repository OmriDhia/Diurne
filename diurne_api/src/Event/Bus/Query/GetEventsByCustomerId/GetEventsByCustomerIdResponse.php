<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEventsByCustomerId;

use App\Common\Bus\Query\QueryResponse;

final class GetEventsByCustomerIdResponse implements QueryResponse
{
    public function __construct(
        public array $customerEventsData
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{customerEvents: array}
     */
    public function toArray(): array
    {
        return [
            'customerEvents' => $this->customerEventsData,
        ];
    }
}
