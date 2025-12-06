<?php

namespace App\Setting\Bus\Command\TransportType;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TransportType;
use App\Setting\Repository\TransportTypeRepository;

class CreateTransportTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportTypeRepository $transportTypeRepository)
    {
    }

    public function __invoke(CreateTransportTypeCommand $command): TransportTypeResponse
    {
        $transportType = new TransportType();
        $transportType->setName($command->getName());

        $this->transportTypeRepository->save($transportType, true);

        return new TransportTypeResponse($transportType);
    }
}
