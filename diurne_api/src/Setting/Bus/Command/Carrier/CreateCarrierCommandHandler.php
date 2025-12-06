<?php

namespace App\Setting\Bus\Command\Carrier;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Carrier;
use App\Setting\Repository\CarrierRepository;

class CreateCarrierCommandHandler implements CommandHandler
{
    public function __construct(private readonly CarrierRepository $carrierRepository)
    {
    }

    public function __invoke(CreateCarrierCommand $command): CarrierResponse
    {
        $carrier = new Carrier();
        $carrier->setName($command->getName());
        $carrier->setContact($command->getContact());
        $carrier->setEmail($command->getEmail());
        $carrier->setPhone($command->getPhone());
        $carrier->setFax($command->getFax());
        $carrier->setAddress($command->getAddress());
        $carrier->setCreatedAt(new DateTimeImmutable());

        $this->carrierRepository->save($carrier, true);

        return new CarrierResponse($carrier);
    }
}
