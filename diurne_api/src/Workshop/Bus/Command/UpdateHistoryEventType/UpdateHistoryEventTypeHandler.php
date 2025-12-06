<?php

namespace App\Workshop\Bus\Command\UpdateHistoryEventType;

use App\Common\Bus\Command\CommandHandler;

use App\Common\Exception\DuplicateValidationResourceException;
use App\Workshop\Repository\HistoryEventTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateHistoryEventTypeHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface     $entityManager,
        private HistoryEventTypeRepository $repository
    )
    {
    }

    /**
     * @throws DuplicateValidationResourceException
     */
    public function __invoke(UpdateHistoryEventTypeCommand $command): UpdateHistoryEventTypeResponse
    {
        $eventType = $this->repository->find($command->id);

        if (!$eventType) {
            throw new \RuntimeException('History event type not found');
        }
        if ($eventType->getName() !== $command->name) {
            $existingType = $this->repository->findOneByName(['name' => $command->name]);

            if ($existingType !== null && $existingType->getId() !== $eventType->getId()) {
                throw new DuplicateValidationResourceException('A history event type with this name already exists');
            }
        }
        $eventType->setName($command->name);
        $this->entityManager->flush();

        return new UpdateHistoryEventTypeResponse($eventType);
    }
}