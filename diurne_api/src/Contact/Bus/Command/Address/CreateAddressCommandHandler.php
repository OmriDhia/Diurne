<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Address;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Contact\Entity\Address;
use App\Contact\Repository\AddressRepository;
use App\Contact\Repository\AddressTypeRepository;
use App\Setting\Repository\CountryRepository;

class CreateAddressCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly AddressRepository $addressRepository,
        private readonly CountryRepository $countryRepository,
        private readonly AddressTypeRepository $addressTypeRepository,
    ) {}

    public function __invoke(CreateAddressCommand $command): AddressResponse
    {
        $address = $this->addressRepository->findOneBy(
            [
                'address1' => $command->getAddress1(),
                'city' => $command->getCity(),
                'zip_code' => $command->getZipCode(),
            ]
        );

        if ($address instanceof Address) {
            throw new DuplicateValidationResourceException();
        }
        /**
         * @var Address
         */
        $address = $this->addressRepository->create(
            [
                'fullName' => $command->getFulName(),
                'address1' => $command->getAddress1(),
                'city' => $command->getCity(),
                'state' => $command->getState(),
                'zip_code' => $command->getZipCode(),
                'is_f_valide' => $command->getIsFValide() ?? true,
                'is_l_valide' => $command->getIsLValide() ?? true,
                'is_wrong' => $command->getIsWrong() ?? false,
                'comment' => $command->getComment() ?? null,
                'phone' => $command->getPhone() ?? null,
                'mobile_phone' => $command->getMobilePhone() ?? null,
            ]
        );
        if ($address) {
            $country = $this->countryRepository->find($command->getCountry());
            if ($country) {
                $address->setCountry($country);
            }
            $addressType = $this->addressTypeRepository->find($command->getAddressType());
            if ($addressType) {
                $address->setAddressType($addressType);
            }

            $this->addressRepository->persist($address);
            $this->addressRepository->flush();
        }

        return new AddressResponse(
            $address->getId(),
            $address->getFullName(),
            $address->getAddress1(),
            $address->getCity(),
            $address->getAddressType(),
            $address->getCountry(),
            $address->getZipCode(),
            $address->getState(),
            $address->isIsFValide(),
            $address->isIsLValide(),
            $address->isIsWrong(),
            $address->getComment(),
            $address->getPhone(),
            $address->getMobilePhone(),

        );
    }
}
