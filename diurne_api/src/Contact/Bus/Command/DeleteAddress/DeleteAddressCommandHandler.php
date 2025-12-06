<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteAddress;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Address;
use App\Contact\Repository\AddressRepository;

class DeleteAddressCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly AddressRepository $addressRepository
    ) {
    }

    public function __invoke(DeleteAddressCommand $command): DeleteAddressResponse
    {
        $address = $this->addressRepository->find((int) $command->getAddressId());

        if (!$address instanceof Address) {
            throw new ResourceNotFoundException();
        }

        $this->addressRepository->remove($address);

        return new DeleteAddressResponse($command->getAddressId());
    }
}
