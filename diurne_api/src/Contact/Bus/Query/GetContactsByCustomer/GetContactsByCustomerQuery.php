<?php

namespace App\Contact\Bus\Query\GetContactsByCustomer;

use App\Common\Bus\Query\Query;

final class GetContactsByCustomerQuery implements Query
{
    public function __construct(
        public int $customerId
    ) {
    }
}
