<?php

namespace App\Workshop\Bus\Command\UpdateHistoryEventCategory;

use App\Common\Bus\Command\CommandHandler;

use App\Common\Exception\DuplicateValidationResourceException;
use App\Workshop\Repository\HistoryEventCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateHistoryEventCategoryHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface         $entityManager,
        private readonly HistoryEventCategoryRepository $repository
    )
    {
    }

    /**
     * @throws DuplicateValidationResourceException
     */
    public function __invoke(UpdateHistoryEventCategoryCommand $command): UpdateHistoryEventCategoryResponse
    {
        $eventCategory = $this->repository->find($command->id);

        if (!$eventCategory) {
            throw new \RuntimeException('History event type not found');
        }
        if ($eventCategory->getName() !== $command->name) {
            $existingCategory = $this->repository->findOneByName(['name' => $command->name]);

            if ($existingCategory !== null && $existingCategory->getId() !== $eventCategory->getId()) {
                throw new DuplicateValidationResourceException('A history event category with this name already exists');
            }
        }
        $eventCategory->setName($command->name);
        $this->entityManager->flush();

        return new UpdateHistoryEventCategoryResponse($eventCategory);
    }
}