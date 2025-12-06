<?php

namespace App\Workshop\Bus\Command\CreateWorkshopRnHistory;

use App\Common\Bus\Command\CommandHandler;
use App\Contact\Entity\Customer;
use App\Event\Entity\Event;
use App\Workshop\Entity\Carpet;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Entity\WorkshopRnHistory;
use App\Workshop\Repository\WorkshopRnHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

class CreateWorkshopRnHistoryHandler implements CommandHandler
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
    public function __invoke(CreateWorkshopRnHistoryCommand $command): WorkshopRnHistoryResponse
    {
        $workshopRnHistory = new WorkshopRnHistory();

        // Set relations
        $eventType = $this->entityManager->getReference(Event::class, $command->eventTypeId);
        $workshopRnHistory->setEventTypeId($eventType);

        $workshopRnHistory->setLocationId($command->locationId);

        $customer = $this->entityManager->getReference(Customer::class, $command->customerId);
        $workshopRnHistory->setCustomerId($customer);

        $workshopOrder = $this->entityManager->getReference(WorkshopOrder::class, $command->workshopOrderId);
        $workshopRnHistory->setWorkshopOrder($workshopOrder);

        $carpetId = $this->entityManager->getReference(Carpet::class, $command->carpetId);
        $workshopRnHistory->setCarpet($carpetId);

        // Set dates
        $workshopRnHistory->setBeginAt(new \DateTime($command->beginAt));
        $workshopRnHistory->setEndAt(new \DateTime($command->endAt));
        $workshopRnHistory->setCreatedAt($command->createdAt ? new \DateTime($command->createdAt) : new \DateTime());
        $workshopRnHistory->setUpdatedAt($command->updatedAt ? new \DateTime($command->updatedAt) : new \DateTime());

        $this->entityManager->persist($workshopRnHistory);
        $this->entityManager->flush();

        return new WorkshopRnHistoryResponse($workshopRnHistory);
    }
}