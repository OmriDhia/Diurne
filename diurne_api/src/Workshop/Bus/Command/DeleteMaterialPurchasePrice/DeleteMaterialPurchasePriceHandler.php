<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteMaterialPurchasePrice;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteMaterialPurchasePriceHandler implements CommandHandler
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param MaterialPurchasePriceRepository $materialPurchasePriceRepository
     */
    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly MaterialPurchasePriceRepository $materialPurchasePriceRepository
    )
    {
    }

    /**
     * @param DeleteMaterialPurchasePriceCommand $command
     * @return DeleteMaterialPurchasePriceResponse
     */
    public function __invoke(DeleteMaterialPurchasePriceCommand $command): DeleteMaterialPurchasePriceResponse
    {
        $materialPurchasePrice = $this->materialPurchasePriceRepository->find($command->id);

        if (!$materialPurchasePrice) {
            throw new ResourceNotFoundException();
        }

        $this->entityManager->remove($materialPurchasePrice);
        $this->entityManager->flush();

        return new DeleteMaterialPurchasePriceResponse($command->id);
    }
}