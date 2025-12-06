<?php

namespace App\CheckingList\Bus\Command\DeleteQualityCheck;

use App\CheckingList\Repository\QualityCheckRepository;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class DeleteQualityCheckHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QualityCheckRepository $repository,
    ) {
    }

    public function __invoke(DeleteQualityCheckCommand $command): DeleteQualityCheckResponse
    {
        $qualityCheck = $this->repository->find($command->id);
        if (!$qualityCheck) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($qualityCheck);
        $this->entityManager->flush();

        return new DeleteQualityCheckResponse($command->id);
    }
}
