<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportType;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\TransportType;

final readonly class GetByIdTransportTypeResponse implements QueryResponse
{
    public function __construct(private ?TransportType $transportType)
    {
    }

    public function toArray(): array
    {
        return $this->transportType ? [
            'id' => $this->transportType->getId(),
            'name' => $this->transportType->getName(),
        ] : [];
    }
}
