<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventTypeCategory;

use App\Common\Bus\Command\CommandHandler;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Entity\HistoryEventTypeCategory;
use Doctrine\ORM\EntityManagerInterface;

class CreateHistoryEventTypeCategoryHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(CreateHistoryEventTypeCategoryCommand $command): HistoryEventTypeCategoryResponse
    {
        $eventType = $this->entityManager->getReference(HistoryEventType::class, $command->eventTypeId);
        $eventCategory = $this->entityManager->getReference(HistoryEventCategory::class, $command->eventCategoryId);

        $category = new HistoryEventTypeCategory();
        $category->setEventTypeId($eventType);
        $category->setEventCategoryId($eventCategory);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return new HistoryEventTypeCategoryResponse($category);
    }
}