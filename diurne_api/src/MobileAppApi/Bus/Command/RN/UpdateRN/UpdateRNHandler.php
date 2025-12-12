<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\RN\UpdateRN;

use App\Common\Bus\Command\CommandHandler;
use App\MobileAppApi\Entity\RN;
use App\MobileAppApi\Entity\Workshop;
use App\MobileAppApi\Repository\RNRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Common\Bus\Command\CommandResponse;

#[AsMessageHandler]
final class UpdateRNHandler implements CommandHandler
{
    public function __construct(
        private readonly RNRepository $repository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(UpdateRNCommand $command): CommandResponse
    {
        $rn = $this->repository->find($command->id);

        if (!$rn) {
            throw new NotFoundHttpException('RN not found');
        }

        if ($command->rnNumber) {
            $rn->setRnNumber($command->rnNumber);
        }
        
        if ($command->workshopId) {
             $workshop = $this->entityManager->getRepository(Workshop::class)->find($command->workshopId);
             if (!$workshop) {
                 throw new NotFoundHttpException('Workshop not found');
             }
             $rn->setWorkshop($workshop);
        }

        $this->repository->save($rn, true);

        return new UpdateRNResponse($rn);
    }
}
