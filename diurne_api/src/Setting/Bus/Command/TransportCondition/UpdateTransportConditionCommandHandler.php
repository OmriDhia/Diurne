<?php

namespace App\Setting\Bus\Command\TransportCondition;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\TransportConditionRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateTransportConditionCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportConditionRepository $transportConditionRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateTransportConditionCommand $command): TransportConditionResponse
    {
        $transportCondition = $this->transportConditionRepository->find((int) $command->getId());

        if (null === $transportCondition) {
            throw new ResourceNotFoundException();
        }
        $transportCondition->setName($command->getName());
        // Add your entity fields here

        $this->transportConditionRepository->save($transportCondition, true);

        return new TransportConditionResponse($transportCondition);
    }
}
