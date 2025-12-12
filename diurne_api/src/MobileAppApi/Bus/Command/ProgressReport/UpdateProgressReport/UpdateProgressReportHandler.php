<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\ProgressReport\UpdateProgressReport;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Repository\ProgressReportRepository;
use App\MobileAppApi\Entity\UserMobileApp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class UpdateProgressReportHandler implements CommandHandler
{
    public function __construct(
        private readonly ProgressReportRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UpdateProgressReportCommand $command): CommandResponse
    {
        $progressReport = $this->repository->find($command->id);

        if (!$progressReport) {
            throw new NotFoundHttpException('ProgressReport not found');
        }

        $progressReport->setState($command->state);
        $progressReport->setIsWoven($command->isWoven);
        $progressReport->setComment($command->comment);

        if ($command->userId) {
            $user = $this->entityManager->getReference(UserMobileApp::class, $command->userId);
            $progressReport->setUser($user);
        }

        $this->repository->save($progressReport, true);

        return new UpdateProgressReportResponse($progressReport);
    }
}
