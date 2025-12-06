<?php

namespace App\Setting\Bus\Command\Carrier;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Carrier;

class CarrierResponse implements CommandResponse
{
    public function __construct(private readonly Carrier $carrier)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->carrier->getId(),
            'name' => $this->carrier->getName(),
            'contact' => $this->carrier->getContact(),
            'email' => $this->carrier->getEmail(),
            'phone' => $this->carrier->getPhone(),
            'fax' => $this->carrier->getFax(),
            'address' => $this->carrier->getAddress(),
        ];
    }
}
