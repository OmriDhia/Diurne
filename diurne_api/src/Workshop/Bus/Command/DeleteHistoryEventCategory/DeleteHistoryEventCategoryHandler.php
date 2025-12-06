<?php

namespace App\Workshop\Bus\Command\DeleteHistoryEventCategory;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\HistoryEventCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteHistoryEventCategoryHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param HistoryEventCategoryRepository $repository
     */
    public function __construct(
        private readonly EntityManagerInterface         $entityManager,
        private readonly HistoryEventCategoryRepository $repository
    )
    {
    }

    /**
     * @param DeleteHistoryEventCategoryCommand $command
     * @return DeleteHistoryEventCategoryResponse
     */
    public function __invoke(DeleteHistoryEventCategoryCommand $command): DeleteHistoryEventCategoryResponse
    {
        $eventCategory = $this->repository->find($command->id);
        if (!$eventCategory) {
            throw new ResourceNotFoundException();
        }
        $this->entityManager->remove($eventCategory);
        $this->entityManager->flush();

        return new DeleteHistoryEventCategoryResponse($command->id);
    }
}