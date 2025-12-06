<?php

namespace App\Setting\Bus\Command\Carrier;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\CarrierRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateCarrierCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarrierRepository $carrierRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateCarrierCommand $command): CarrierResponse
    {
        $carrier = $this->carrierRepository->find((int) $command->getId());

        if (null === $carrier) {
            throw new ResourceNotFoundException();
        }
        $carrier->setName($command->getName());
        $carrier->setContact($command->getContact());
        $carrier->setEmail($command->getEmail());
        $carrier->setPhone($command->getPhone());
        $carrier->setFax($command->getFax());
        $carrier->setAddress($command->getAddress());
        $carrier->setUpdatedAt(new DateTimeImmutable());

        $this->carrierRepository->save($carrier, true);

        return new CarrierResponse($carrier);
    }
}
