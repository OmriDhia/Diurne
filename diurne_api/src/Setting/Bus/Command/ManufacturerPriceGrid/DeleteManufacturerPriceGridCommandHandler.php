<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteManufacturerPriceGridCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ManufacturerPriceGridRepository $priceGridRepository
    ) {}

    public function __invoke(DeleteManufacturerPriceGridCommand $command): void
    {
        $priceGrid = $this->priceGridRepository->find($command->getId());

        if (!$priceGrid) {
            throw new ResourceNotFoundException('Price grid not found.');
        }

        $this->entityManager->remove($priceGrid);
        $this->entityManager->flush();
    }
}
