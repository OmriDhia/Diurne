<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Address;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Address;
use App\Contact\Repository\AddressRepository;
use App\Contact\Entity\AddressType;
use App\Setting\Entity\Country;
use App\Contact\Repository\AddressTypeRepository;
use App\Setting\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UpdateAddressCommandHandler implements CommandHandler
{
    public function __construct(
        private AddressRepository $addressRepository,
        private CountryRepository $countryRepository,
        private AddressTypeRepository $addressTypeRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdateAddressCommand $command): AddressResponse
    {
        $this->entityManager->beginTransaction();

        try {
            $address = $this->fetchAddress($command->addressId);
            $country = $this->fetchCountry($command->countryId);
            $addressType = $this->fetchAddressType($command->addressTypeId);

            // Update the address entity
            $address
                ->setFullName($command->fullName)
                ->setAddress1($command->address1)
                ->setCity($command->city)
                ->setZipCode($command->zipCode)
                ->setState($command->state)
                ->setCountry($country)
                ->setAddressType($addressType)
                ->setIsFValide($command->isFValide ?? true)
                ->setIsLValide($command->isLValide ?? true)
                ->setIsWrong($command->isWrong ?? false)
                ->setComment($command->comment)
                ->setPhone($command->phone)
                ->setMobilePhone($command->mobilePhone);

            $this->entityManager->persist($address);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return new AddressResponse(
                id: $address->getId(),
                fullName: $address->getFullName(),
                address1: $address->getAddress1(),
                city: $address->getCity(),
                addressType: $address->getAddressType(),
                country: $address->getCountry(),
                zipCode: $address->getZipCode(),
                state: $address->getState(),
                isFValide: $address->isIsFValide(),
                isLValide: $address->isIsLValide(),
                isWrong: $address->isIsWrong(),
                comment: $address->getComment(),
                phone: $address->getPhone(),
                mobilePhone: $address->getMobilePhone(),
            );
        } catch (Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    private function fetchAddress(int $addressId): Address
    {
        $address = $this->addressRepository->find($addressId);
        if (!$address instanceof Address) {
            throw new ResourceNotFoundException("Address with ID {$addressId} not found.");
        }
        return $address;
    }

    private function fetchCountry(int $countryId): Country
    {
        $country = $this->countryRepository->find($countryId);
        if (!$country instanceof Country) {
            throw new ResourceNotFoundException("Country with ID {$countryId} not found.");
        }
        return $country;
    }

    private function fetchAddressType(int $addressTypeId): AddressType
    {
        $addressType = $this->addressTypeRepository->find($addressTypeId);
        if (!$addressType instanceof AddressType) {
            throw new ResourceNotFoundException("Address type with ID {$addressTypeId} not found.");
        }
        return $addressType;
    }
}
