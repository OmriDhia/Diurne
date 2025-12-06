<?php

namespace App\Setting\Bus\Command\MaterialPrice;

use RuntimeException;
use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Repository\MaterialPriceRepository;

class DeleteMaterialPriceCommandHandler implements CommandHandler
{
    public function __construct(private readonly MaterialPriceRepository $materialPriceRepository)
    {
    }

    public function __invoke(DeleteMaterialPriceCommand $command): MaterialPriceResponse
    {
        $materialPrice = $this->materialPriceRepository->find($command->id);
        if (!$materialPrice) {
            throw new RuntimeException('Material price not found', 404);
        }

        try {
            $this->materialPriceRepository->remove($materialPrice);
            $this->materialPriceRepository->flush();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to delete material price: ' . $e->getMessage(), 0, $e);
        }

        return new MaterialPriceResponse($materialPrice);
    }
}

