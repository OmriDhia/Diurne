<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Carrier;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Carrier;

final readonly class GetByIdCarrierResponse implements QueryResponse
{
    public function __construct(private ?Carrier $carrier)
    {
    }

    public function toArray(): array
    {
        return $this->carrier ? [
            'id' => $this->carrier->getId(),
            'name' => $this->carrier->getName(),
            'contact' => $this->carrier->getContact(),
            'email' => $this->carrier->getEmail(),
            'phone' => $this->carrier->getPhone(),
            'fax' => $this->carrier->getFax(),
            'address' => $this->carrier->getAddress(),
            'createdAt' => $this->carrier->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->carrier->getUpdatedAt()->format('Y-m-d H:i:s'),
        ] : [];
    }
}
