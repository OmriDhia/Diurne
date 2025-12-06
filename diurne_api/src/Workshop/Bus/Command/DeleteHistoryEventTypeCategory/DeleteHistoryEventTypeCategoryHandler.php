<?php

namespace App\Workshop\Bus\Command\DeleteHistoryEventTypeCategory;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Entity\HistoryEventTypeCategory;
use App\Workshop\Repository\HistoryEventTypeCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteHistoryEventTypeCategoryHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface             $entityManager,
        private HistoryEventTypeCategoryRepository $repository
    )
    {
    }

    public function __invoke(DeleteHistoryEventTypeCategoryCommand $command): DeleteHistoryEventTypeCategoryResponse
    {
        $category = $this->repository->find($command->id);

        if (!$category) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return new DeleteHistoryEventTypeCategoryResponse($command->id);
    }
}