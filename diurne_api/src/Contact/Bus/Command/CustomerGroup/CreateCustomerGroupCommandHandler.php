<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\CustomerGroup;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Contact\Entity\CustomerGroup;
use App\Contact\Repository\CustomerGroupRepository;

class CreateCustomerGroupCommandHandler implements CommandHandler
{
    public function __construct(private readonly CustomerGroupRepository $customerGroupRepository)
    {
    }

    public function __invoke(CreateCustomerGroupCommand $command): CustomerGroupResponse
    {
        $customerGroup = $this->customerGroupRepository->findByName($command->getName());

        if ($customerGroup instanceof CustomerGroup) {
            throw new DuplicateValidationResourceException();
        }
        $customerGroup = $this->customerGroupRepository->create(
            [
                'name' => $command->getName(),
            ]
        );

        return new CustomerGroupResponse($customerGroup->getId(), $customerGroup->getName());
    }
}
