<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\CommandResponse;
use App\Contact\Entity\Customer;
use App\User\Entity\User;

final readonly class AssignCommercialToCustomerResponse implements CommandResponse
{
    public function __construct(
        private Customer $customer,
        private User $user
    ) {
    }

    /**
     * @return (int|null)[]
     *
     * @psalm-return array{customerId: int|null, commercialId: int|null}
     */
    public function toArray(): array
    {
        return [
            'customerId' => $this->customer->getId(),
            'commercialId' => $this->user->getId(),
        ];
    }
}
