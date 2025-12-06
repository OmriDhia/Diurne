<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopOrder;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityManagerInterface;


class DeleteWorkshopOrderHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param WorkshopOrderRepository $orderRepository
     */
    public function __construct(
        private readonly EntityManagerInterface  $entityManager,
        private readonly WorkshopOrderRepository $orderRepository
    )
    {
    }

    /**
     * @param DeleteWorkshopOrderCommand $command
     * @return DeleteWorkshopOrderResponse
     */
    public function __invoke(DeleteWorkshopOrderCommand $command): DeleteWorkshopOrderResponse
    {
        $workshopOrder = $this->orderRepository->find($command->id);

        if (!$workshopOrder) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($workshopOrder);
        $this->entityManager->flush();

        return new DeleteWorkshopOrderResponse($command->id);
    }
}