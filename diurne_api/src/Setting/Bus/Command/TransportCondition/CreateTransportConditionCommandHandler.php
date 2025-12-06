<?php

namespace App\Setting\Bus\Command\TransportCondition;

use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\TransportCondition;
use App\Setting\Repository\TransportConditionRepository;

class CreateTransportConditionCommandHandler implements CommandHandler
{
    public function __construct(private readonly TransportConditionRepository $transportConditionRepository)
    {
    }

    public function __invoke(CreateTransportConditionCommand $command): TransportConditionResponse
    {
        $transportCondition = new TransportCondition();
        $transportCondition->setName($command->getName());

        $this->transportConditionRepository->save($transportCondition, true);

        return new TransportConditionResponse($transportCondition);
    }
}
