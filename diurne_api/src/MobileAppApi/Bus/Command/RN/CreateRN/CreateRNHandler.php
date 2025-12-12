<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\CreateRN;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\Workshop;
use App\MobileAppApi\Repository\RNRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class CreateRNHandler implements CommandHandler
{
    public function __construct(
        private readonly RNRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateRNCommand $command): CommandResponse
    {
        $workshop = $this->entityManager->getRepository(Workshop::class)->find($command->workshopId);
        if (!$workshop) {
            throw new NotFoundHttpException('Workshop not found');
        }

        $rn = new RN();
        $rn->setRnNumber($command->rnNumber);
        $rn->setWorkshop($workshop);

        $this->repository->save($rn, true);

        return new CreateRNResponse($rn);
    }
}
