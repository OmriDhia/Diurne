<?php

namespace App\Workshop\Bus\Command\DeleteWorkshopRnHistory;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopRnHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteWorkshopRnHistoryHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private WorkshopRnHistoryRepository $workshopRnHistoryRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(DeleteWorkshopRnHistoryCommand $command): DeleteWorkshopRnHistoryResponse
    {
        $workshopRnHistory = $this->workshopRnHistoryRepository->find($command->id);

        if (!$workshopRnHistory) {
            throw new ResourceNotFoundException();
        }
        $this->entityManager->remove($workshopRnHistory);
        $this->entityManager->flush();

        return new DeleteWorkshopRnHistoryResponse($workshopRnHistory);
    }
}