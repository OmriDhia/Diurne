<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomerById;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a customer by their ID.
 */
final class GetCustomerByIdQuery implements Query
{
    /**
     * Constructor for GetCustomerByIdQuery.
     *
     * @param string $customerId the unique identifier of the customer
     */
    public function __construct(
        public string $customerId
    ) {
    }

    public function customerId(): string
    {
        return $this->customerId;
    }
}
