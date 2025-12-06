<?php

namespace App\Contact\Bus\Query\GetContactsByCustomer;

use App\Common\Bus\Query\QueryResponse;

final class GetContactsByCustomerResponse implements QueryResponse
{
    public function __construct(
        public array $contacts
    ) {
    }

    public function toArray(): array
    {
        return [
            'contacts' => $this->contacts,
        ];
    }
}
