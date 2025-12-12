<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\UpdateWorkshop;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\Workshop;
use App\MobileAppApi\Repository\WorkshopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class UpdateWorkshopHandler implements CommandHandler
{
    public function __construct(
        private readonly WorkshopRepository $repository
    ) {
    }

    public function __invoke(UpdateWorkshopCommand $command): CommandResponse
    {
        $workshop = $this->repository->find($command->id);

        if (!$workshop) {
            throw new NotFoundHttpException('Workshop not found');
        }

        if ($command->name) {
            $workshop->setName($command->name);
        }
        if ($command->carpetRnPrefix) {
            $workshop->setCarpetRnPrefix($command->carpetRnPrefix);
        }
        if ($command->sampleRnPrefix) {
            $workshop->setSampleRnPrefix($command->sampleRnPrefix);
        }

        $this->repository->save($workshop, true);

        return new UpdateWorkshopResponse($workshop);
    }
}
