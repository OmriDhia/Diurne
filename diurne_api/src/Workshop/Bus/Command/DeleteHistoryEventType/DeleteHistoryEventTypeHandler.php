<?php

namespace App\Workshop\Bus\Command\DeleteHistoryEventType;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\HistoryEventTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteHistoryEventTypeHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param HistoryEventTypeRepository $repository
     */
    public function __construct(
        private readonly EntityManagerInterface     $entityManager,
        private readonly HistoryEventTypeRepository $repository
    )
    {
    }

    /**
     * @param DeleteHistoryEventTypeCommand $command
     * @return DeleteHistoryEventTypeResponse
     */
    public function __invoke(DeleteHistoryEventTypeCommand $command): DeleteHistoryEventTypeResponse
    {
        $eventType = $this->repository->find($command->id);

        if (!$eventType) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($eventType);
        $this->entityManager->flush();

        return new DeleteHistoryEventTypeResponse($command->id);
    }
}