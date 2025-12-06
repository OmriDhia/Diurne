<?php

namespace App\Setting\Bus\Command\TarifGroup;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateTarifGroupCommandHandler implements CommandHandler
{
    public function __construct(private readonly TarifGroupRepository $tarifGroupRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateTarifGroupCommand $command): TarifGroupResponse
    {
        $tarifGroup = $this->tarifGroupRepository->find($command->getId());

        if (null === $tarifGroup) {
            throw new ResourceNotFoundException();
        }
        $tarifGroup->setYear($command->getYear());

        $this->tarifGroupRepository->save($tarifGroup, true);

        return new TarifGroupResponse($tarifGroup);
    }
}
