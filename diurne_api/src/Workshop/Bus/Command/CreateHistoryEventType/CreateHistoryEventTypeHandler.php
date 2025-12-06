<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventType;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Repository\HistoryEventTypeRepository;
use Doctrine\ORM\EntityManagerInterface;


class CreateHistoryEventTypeHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface     $entityManager,
        private readonly HistoryEventTypeRepository $eventTypeRepository
    )
    {
    }

    /**
     * @param CreateHistoryEventTypeCommand $command
     * @return HistoryEventTypeResponse
     * @throws DuplicateValidationResourceException
     */
    public function __invoke(CreateHistoryEventTypeCommand $command): HistoryEventTypeResponse
    {
        $existingType = $this->eventTypeRepository->findOneByName(['name' => $command->name]);
        if ($existingType !== null) {
            throw new DuplicateValidationResourceException("A history event type with this name already exists");
        }
        $eventType = new HistoryEventType();
        $eventType->setName($command->name);

        $this->entityManager->persist($eventType);
        $this->entityManager->flush();

        return new HistoryEventTypeResponse($eventType);
    }
}