<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEventsByCustomerId;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve events by customer ID with an optional filter by contremarque ID.
 */
final class GetEventsByCustomerIdQuery implements Query
{
    /**
     * Constructor for GetEventsByCustomerIdQuery.
     *
     * @param string      $customerId     the unique identifier of the customer
     * @param string|null $contremarqueId the optional identifier for filtering events by contremarque
     */
    public function __construct(
        public string $customerId,
        public ?string $contremarqueId = null
    ) {
    }

    public function customerId(): string
    {
        return $this->customerId;
    }

    public function contremarqueId(): ?string
    {
        return $this->contremarqueId;
    }
}
