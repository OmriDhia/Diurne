<?php

namespace App\Workshop\Bus\Command\UpdateWorkshopRnHistory;

use App\Common\Bus\Command\CommandHandler;

use App\Contact\Entity\Customer;
use App\Event\Entity\Event;
use App\Workshop\Entity\Carpet;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopRnHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

class UpdateWorkshopRnHistoryHandler implements CommandHandler
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private WorkshopRnHistoryRepository $workshopRnHistoryRepository
    )
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateWorkshopRnHistoryCommand $command): WorkshopRnHistoryUpdateResponse
    {
        $workshopRnHistory = $this->workshopRnHistoryRepository->find($command->id);

        if (!$workshopRnHistory) {
            throw new \RuntimeException('Workshop RN history not found');
        }

        // Update relations if provided
        if ($command->eventTypeId !== null) {
            $eventType = $this->entityManager->getReference(Event::class, $command->eventTypeId);
            $workshopRnHistory->setEventTypeId($eventType);
        }

        if ($command->locationId !== null) {
            $workshopRnHistory->setLocationId($command->locationId);
        }

        if ($command->customerId !== null) {
            $customer = $this->entityManager->getReference(Customer::class, $command->customerId);
            $workshopRnHistory->setCustomerId($customer);
        }

        if ($command->workshopOrderId !== null) {
            $workshopOrder = $this->entityManager->getReference(WorkshopOrder::class, $command->workshopOrderId);
            $workshopRnHistory->setWorkshopOrder($workshopOrder);
        }

        // Update dates if provided
        if ($command->beginAt !== null) {
            $workshopRnHistory->setBeginAt(new \DateTime($command->beginAt));
        }

        if ($command->endAt !== null) {
            $workshopRnHistory->setEndAt(new \DateTime($command->endAt));
        }
        if ($command->carpetId) {
            $carpet = $this->entityManager->getReference(Carpet::class, $command->carpetId);
            $workshopRnHistory->setCarpet($carpet);
        }
        $workshopRnHistory->setUpdatedAt($command->updatedAt ? new \DateTime($command->updatedAt) : new \DateTime());

        $this->entityManager->flush();

        return new WorkshopRnHistoryUpdateResponse($workshopRnHistory);
    }

}