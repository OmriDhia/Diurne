<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomerById;

use App\Common\Bus\Query\QueryResponse;

final class GetCustomerByIdResponse implements QueryResponse
{
    /**
     * GetCommercialsResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $customerData
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{customer: array}
     */
    public function toArray(): array
    {
        return [
            'customer' => $this->customerData,
        ];
    }
}
