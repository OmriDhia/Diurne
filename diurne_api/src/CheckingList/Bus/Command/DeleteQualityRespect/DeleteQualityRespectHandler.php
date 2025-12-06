<?php

namespace App\CheckingList\Bus\Command\DeleteQualityRespect;

use App\CheckingList\Repository\QualityRespectRepository;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class DeleteQualityRespectHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QualityRespectRepository $repository,
    ) {
    }

    public function __invoke(DeleteQualityRespectCommand $command): DeleteQualityRespectResponse
    {
        $respect = $this->repository->find($command->id);
        if (!$respect) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($respect);
        $this->entityManager->flush();

        return new DeleteQualityRespectResponse($command->id);
    }
}
