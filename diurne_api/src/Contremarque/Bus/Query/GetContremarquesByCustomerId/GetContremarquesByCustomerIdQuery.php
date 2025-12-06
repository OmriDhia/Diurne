<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetContremarquesByCustomerId;

use App\Common\Bus\Query\Query;

final readonly class GetContremarquesByCustomerIdQuery implements Query
{
    public function __construct(private int $customerId)
    {
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }
}
