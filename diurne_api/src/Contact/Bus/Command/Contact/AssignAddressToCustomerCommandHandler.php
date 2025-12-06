<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Address;
use App\Contact\Entity\Customer;
use App\Contact\Repository\AddressRepository;
use App\Contact\Repository\CustomerRepository;

class AssignAddressToCustomerCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly AddressRepository $addressRepository,
        private readonly CustomerRepository $customerRepository
    ) {
    }

    public function __invoke(AssignAddressToCustomerCommand $command): AssignAddressToCustomerResponse
    {
        $address = $this->addressRepository->find((int) $command->getAddressId());

        if (!$address instanceof Address) {
            throw new ResourceNotFoundException();
        }
        /**
         * @var Customer
         */
        $customer = $this->customerRepository->find((int) $command->getCustomerId());

        if (!$customer instanceof Customer) {
            throw new ResourceNotFoundException();
        }

        $customer->addAddress($address);
        $this->customerRepository->persist($customer);
        $this->customerRepository->persist($address);
        $this->customerRepository->flush();

        return new AssignAddressToCustomerResponse($customer, $address);
    }
}
