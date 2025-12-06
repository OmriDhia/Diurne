<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomerGroups;

use App\Common\Bus\Query\QueryResponse;

final class GetCustomerGroupsResponse implements QueryResponse
{
    /**
     * GetCustomerGroupResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $customerGroup
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{customerGroup: array}
     */
    public function toArray(): array
    {
        return [
            'customerGroup' => $this->customerGroup,
        ];
    }
}
