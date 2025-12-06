<?php

namespace App\Workshop\Bus\Command\UpdateHistoryEventTypeCategory;

use App\Common\Bus\Command\CommandHandler;
use App\Workshop\Bus\Command\CreateHistoryEventTypeCategory\HistoryEventTypeCategoryResponse;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Repository\HistoryEventTypeCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateHistoryEventTypeCategoryHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface             $entityManager,
        private readonly HistoryEventTypeCategoryRepository $repository
    )
    {
    }

    public function __invoke(UpdateHistoryEventTypeCategoryCommand $command): HistoryEventTypeCategoryResponse
    {
        $category = $this->repository->find($command->id);

        if (!$category) {
            throw new \RuntimeException('History event type category not found');
        }

        $eventType = $this->entityManager->getReference(HistoryEventType::class, $command->eventTypeId);
        $eventCategory = $this->entityManager->getReference(HistoryEventCategory::class, $command->eventCategoryId);

        $category->setEventType($eventType);
        $category->setEventCategory($eventCategory);

        $this->entityManager->flush();

        return new HistoryEventTypeCategoryResponse($category);
    }
}