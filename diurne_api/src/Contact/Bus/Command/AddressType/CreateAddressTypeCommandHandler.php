<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\AddressType;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Contact\Entity\AddressType;
use App\Contact\Repository\AddressTypeRepository;

class CreateAddressTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly AddressTypeRepository $addressTypeRepository)
    {
    }

    public function __invoke(CreateAddressTypeCommand $command): AddressTypeResponse
    {
        $addressType = $this->addressTypeRepository->findByName($command->getName());

        if ($addressType instanceof AddressType) {
            throw new DuplicateValidationResourceException();
        }
        $addressType = $this->addressTypeRepository->create(
            [
                'name' => $command->getName(),
            ]
        );

        return new AddressTypeResponse($addressType->getId(), $addressType->getName());
    }
}
