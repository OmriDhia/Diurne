<?php

namespace App\Setting\Bus\Command\TransportType;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\TransportTypeRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateTransportTypeCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportTypeRepository $transportTypeRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateTransportTypeCommand $command): TransportTypeResponse
    {
        $transportType = $this->transportTypeRepository->find((int) $command->getId());

        if (null === $transportType) {
            throw new ResourceNotFoundException();
        }
        $transportType->setName($command->getName());
        // Add your entity fields here

        $this->transportTypeRepository->save($transportType, true);

        return new TransportTypeResponse($transportType);
    }
}
