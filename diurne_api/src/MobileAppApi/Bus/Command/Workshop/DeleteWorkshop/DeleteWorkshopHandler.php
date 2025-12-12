<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\Workshop\DeleteWorkshop;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Repository\WorkshopRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsMessageHandler]
final class DeleteWorkshopHandler implements CommandHandler
{
    public function __construct(
        private readonly WorkshopRepository $repository
    ) {
    }

    public function __invoke(DeleteWorkshopCommand $command): void
    {
        $workshop = $this->repository->find($command->id);

        if (!$workshop) {
            throw new NotFoundHttpException('Workshop not found');
        }

        $this->repository->remove($workshop, true);
    }
}
