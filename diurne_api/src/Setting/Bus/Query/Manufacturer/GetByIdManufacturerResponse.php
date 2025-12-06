<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Manufacturer;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Manufacturer;

final readonly class GetByIdManufacturerResponse implements QueryResponse
{
    public function __construct(private ?Manufacturer $manufacturer)
    {
    }

    public function toArray(): array
    {
        return $this->manufacturer ? $this->manufacturer->toArray() : [];
    }
}
