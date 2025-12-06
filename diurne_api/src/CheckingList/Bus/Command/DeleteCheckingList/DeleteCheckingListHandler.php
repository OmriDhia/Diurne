<?php

namespace App\CheckingList\Bus\Command\DeleteCheckingList;

use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class DeleteCheckingListHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CheckingListRepository $checkingListRepository
    ) {
    }

    public function __invoke(DeleteCheckingListCommand $command): DeleteCheckingListResponse
    {
        $checkingList = $this->checkingListRepository->find($command->id);
        if (!$checkingList) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($checkingList);
        $this->entityManager->flush();

        return new DeleteCheckingListResponse($command->id);
    }
}
