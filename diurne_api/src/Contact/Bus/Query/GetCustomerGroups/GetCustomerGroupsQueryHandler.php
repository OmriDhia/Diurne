<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCustomerGroups;

use App\Common\Bus\Query\QueryHandler;
use App\Contact\Repository\CustomerGroupRepository;

final readonly class GetCustomerGroupsQueryHandler implements QueryHandler
{
    public function __construct(private CustomerGroupRepository $customerGroupRepository)
    {
    }

    public function __invoke(GetCustomerGroupsQuery $query)
    {
        $customerGroups = $this->customerGroupRepository->findAll();

        $formattedCustomerGroups = array_map(fn($customerGroup) => [
            'customerGroup_id' => $customerGroup->getId(),
            'name' => $customerGroup->getName(),
        ], $customerGroups);

        return new GetCustomerGroupsResponse(
            $formattedCustomerGroups
        );
    }
}
