<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\CreateWorkshop;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\Workshop;
use App\MobileAppApi\Repository\WorkshopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class CreateWorkshopHandler implements CommandHandler
{
    public function __construct(
        private readonly WorkshopRepository $repository
    ) {
    }

    public function __invoke(CreateWorkshopCommand $command): CommandResponse
    {
        $workshop = new Workshop();
        $workshop->setName($command->name);
        $workshop->setCarpetRnPrefix($command->carpetRnPrefix);
        $workshop->setSampleRnPrefix($command->sampleRnPrefix);

        $this->repository->save($workshop, true);

        return new CreateWorkshopResponse($workshop);
    }
}
