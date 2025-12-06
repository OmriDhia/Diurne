<?php

namespace App\Workshop\Bus\Command\CreateMaterialPurchasePrice;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Entity\MaterialPurchasePrice;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateMaterialPurchasePriceHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface  $entityManager,
        private readonly WorkshopOrderRepository $workshopOrderRepository
    ) {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(CreateMaterialPurchasePriceCommand $command): MaterialPurchasePriceResponse
    {
        $workshopOrder = $this->workshopOrderRepository->find($command->productionOrderId);
        if (!$workshopOrder) {
            throw new ResourceNotFoundException();
        }
        $workshopInformation = $workshopOrder->getWorkshopInformation();

        $materialPurchasePrice = new MaterialPurchasePrice();
        $materialPurchasePrice->setMaterialId($command->materialId);
        $materialPurchasePrice->setPrice($command->price);
        $materialPurchasePrice->setWorkshopInformation($workshopInformation);
        $materialPurchasePrice->setWorkshopOrder($workshopOrder);

        $this->entityManager->persist($materialPurchasePrice);
        $this->entityManager->flush();

        return new MaterialPurchasePriceResponse($materialPurchasePrice, $workshopInformation);
    }
}