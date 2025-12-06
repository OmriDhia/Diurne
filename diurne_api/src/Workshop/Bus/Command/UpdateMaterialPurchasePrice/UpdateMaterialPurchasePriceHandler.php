<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateMaterialPurchasePrice;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateMaterialPurchasePriceHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param MaterialPurchasePriceRepository $materialPurchasePriceRepository
     * @param WorkshopOrderRepository $workshopOrderRepository
     */
    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly MaterialPurchasePriceRepository $materialPurchasePriceRepository,
        private readonly WorkshopOrderRepository         $workshopOrderRepository
    )
    {
    }

    /**
     * @param UpdateMaterialPurchasePriceCommand $command
     * @return UpdateMaterialPurchasePriceUpdateResponse
     * @throws ResourceNotFoundException
     */
    public function __invoke(UpdateMaterialPurchasePriceCommand $command): UpdateMaterialPurchasePriceUpdateResponse
    {
        $materialPurchasePrice = $this->materialPurchasePriceRepository->find($command->id);

        if (!$materialPurchasePrice) {
            throw new ResourceNotFoundException();
        }

        if ($command->materialId !== null) {
            $materialPurchasePrice->setMaterialId($command->materialId);
        }

        if ($command->price !== null) {
            $materialPurchasePrice->setPrice($command->price);
        }

        if ($command->productionOrderId !== null) {
            $workshopOrder = $this->workshopOrderRepository->find($command->productionOrderId);
            if (!$workshopOrder) {
                throw new ResourceNotFoundException();
            }
            $materialPurchasePrice->setWorkshopInformation($workshopOrder->getWorkshopInformation());
            $materialPurchasePrice->setWorkshopOrder($workshopOrder);
        }
        $this->entityManager->flush();

        return new UpdateMaterialPurchasePriceUpdateResponse($materialPurchasePrice);
    }
}