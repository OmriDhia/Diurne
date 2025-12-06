<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\TarifGroup;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Setting\Entity\TarifGroup;
use App\Setting\Repository\TarifGroupRepository;

class CreateTarifGroupCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly TarifGroupRepository $tarifGroupRepository
    ) {
    }

    public function __invoke(CreateTarifGroupCommand $command): TarifGroupResponse
    {
        $existingTarifGroup = $this->tarifGroupRepository->findOneByYear($command->getYear());
        if ($existingTarifGroup) {
            throw new ValidationException(['There is another tariff group with the same year.']);
        }

        $tarifGroup = new TarifGroup();
        $tarifGroup->setYear($command->getYear());

        $this->tarifGroupRepository->persist($tarifGroup);
        $this->tarifGroupRepository->flush();

        return new TarifGroupResponse($tarifGroup);
    }
}
