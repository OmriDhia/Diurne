<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\ProgressReport\CreateProgressReport;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\ProgressReport;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\UserMobileApp;
use App\MobileAppApi\Repository\ProgressReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class CreateProgressReportHandler implements CommandHandler
{
    public function __construct(
        private readonly ProgressReportRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateProgressReportCommand $command): CommandResponse
    {
        $rn = $this->entityManager->getRepository(RN::class)->find($command->rnId);
        if (!$rn) {
            throw new NotFoundHttpException('RN not found');
        }

        $progressReport = new ProgressReport();
        $progressReport->setRn($rn);
        $progressReport->setState($command->state);
        $progressReport->setIsWoven($command->isWoven);
        $progressReport->setComment($command->comment);

        if ($command->userId) {
            $user = $this->entityManager->getReference(UserMobileApp::class, $command->userId);
            $progressReport->setUser($user);
        }

        $this->repository->save($progressReport, true);

        return new CreateProgressReportResponse($progressReport);
    }
}
