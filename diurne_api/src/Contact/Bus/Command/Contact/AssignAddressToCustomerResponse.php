<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\CommandResponse;
use App\Contact\Entity\Address;
use App\Contact\Entity\Customer;

final readonly class AssignAddressToCustomerResponse implements CommandResponse
{
    public function __construct(
        private Customer $customer,
        private Address $address
    ) {
    }

    /**
     * @return (int|null)[]
     *
     * @psalm-return array{customerId: int|null, addressId: int|null}
     */
    public function toArray(): array
    {
        return [
            'customerId' => $this->customer->getId(),
            'addressId' => $this->address->getId(),
        ];
    }
}
