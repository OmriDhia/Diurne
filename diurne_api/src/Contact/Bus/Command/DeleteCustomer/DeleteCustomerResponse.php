<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteCustomer;

use App\Common\Bus\Command\CommandResponse;

final class DeleteCustomerResponse implements CommandResponse
{
    public function __construct(
        public int $customerId,
    ) {
    }

    /**
     * @return int[]
     *
     * @psalm-return array{customerId: int}
     */
    public function toArray(): array
    {
        return [
            'customerId' => $this->customerId,
        ];
    }
}
