<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteCustomer;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Customer;
use App\Contact\Repository\CustomerRepository;

class DeleteCustomerCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CustomerRepository $customerRepository
    ) {
    }

    public function __invoke(DeleteCustomerCommand $command): DeleteCustomerResponse
    {
        $customer = $this->customerRepository->find((int) $command->getCustomerId());

        if (!$customer instanceof Customer) {
            throw new ResourceNotFoundException();
        }
        $customer->setDeletedAt(new DateTimeImmutable());
        $customer->setDeletedBy($command->getUser()->getEmail());
        $this->customerRepository->persist($customer);
        $this->customerRepository->flush();

        return new DeleteCustomerResponse($command->getCustomerId());
    }
}
