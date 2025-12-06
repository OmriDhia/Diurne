<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportType;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TransportType;

class TransportTypeQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $transportTypes)
    {
    }

    public function toArray(): array
    {
        /* @var TransportType $transportType */
        return array_map(fn($transportType) => $transportType->toArray(), $this->transportTypes);
    }
}
