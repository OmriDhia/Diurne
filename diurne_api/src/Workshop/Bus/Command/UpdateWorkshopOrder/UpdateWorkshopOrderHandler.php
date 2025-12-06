<?php

namespace App\Workshop\Bus\Command\UpdateWorkshopOrder;

use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Contremarque\Repository\ImageCommandRepository;
use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopInformationRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class UpdateWorkshopOrderHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly WorkshopOrderRepository       $orderRepository,
        private readonly WorkshopInformationRepository $informationRepository,
        private readonly ImageCommandRepository        $imageCommandRepository,
    )
    {
    }

    public function __invoke(UpdateWorkshopOrderCommand $command): UpdateWorkshopOrderResponse
    {
        $workshopOrder = $this->orderRepository->find($command->id);

        if (!$workshopOrder) {
            throw new EntityNotFoundException(
                sprintf('WorkshopOrder with id %d not found', $command->id)
            );
        }

        // Update only the fields that were provided in the command
        if ($command->reference !== null) {
            $workshopOrder->setReference($command->reference);
        }

        if ($command->imageCommandId !== null) {
            $imageCommand = $this->imageCommandRepository->find($command->imageCommandId);
            if (!$imageCommand) {
                throw new EntityNotFoundException(
                    sprintf('$imageCommand  with id %d not found', $command->imageCommandId)
                );
            }
            $workshopOrder->setImageCommandId($command->imageCommandId);
        }

        if ($command->workshopInformationId !== null) {
            $workshopInformation = $this->informationRepository->find($command->workshopInformationId);
            if (!$workshopInformation) {
                throw new EntityNotFoundException(
                    sprintf('WorkshopInformation with id %d not found', $command->workshopInformationId)
                );
            }
            $workshopOrder->setWorkshopInformation($workshopInformation);
        }

        $workshopOrder->setUpdatedAt($command->updatedAt ?? new DateTime());

        $this->entityManager->flush();

        return new UpdateWorkshopOrderResponse($workshopOrder);
    }
}