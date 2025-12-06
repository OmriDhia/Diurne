<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\Police;

use App\Common\Exception\ValidationException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Police;
use App\Setting\Repository\PoliceRepository;

class CreatePoliceCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly PoliceRepository $policeRepository
    )
    {
    }

    public function __invoke(CreatePoliceCommand $command): PoliceResponse
    {
        $existingPolice = $this->policeRepository->findOneByLabel($command->getLabel());

        if ($existingPolice instanceof Police) {
            throw new ValidationException(['There is already a police with the same label.']);
        }

        $police = new Police();
        $police->setLabel($command->getLabel());

        $this->policeRepository->persist($police);
        $this->policeRepository->flush();

        return new PoliceResponse($police);
    }
}
