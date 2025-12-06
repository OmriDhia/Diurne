<?php

namespace App\Setting\Bus\Command\TransportType;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\TransportType;

class TransportTypeResponse implements CommandResponse
{
    public function __construct(private readonly TransportType $transportType)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->transportType->getId(),
            'name' => $this->transportType->getName(),
        ];
    }
}
