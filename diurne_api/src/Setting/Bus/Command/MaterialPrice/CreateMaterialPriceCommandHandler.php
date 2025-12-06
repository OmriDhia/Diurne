<?php

namespace App\Setting\Bus\Command\MaterialPrice;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\MaterialPrice;
use App\Setting\Repository\MaterialPriceRepository;
use App\Setting\Repository\MaterialRepository;

class CreateMaterialPriceCommandHandler implements CommandHandler
{
    public function __construct(private readonly MaterialPriceRepository $materialPriceRepository, private readonly MaterialRepository $materialRepository)
    {
    }

    public function __invoke(CreateMaterialPriceCommand $command): MaterialPriceResponse
    {
        $material = $this->materialRepository->find($command->getMaterialId());
        if (!$material) {
            throw new InvalidArgumentException('Material not found');
        }
        $materialPrice = new MaterialPrice();
        $materialPrice->setMaterial($material);
        $materialPrice->setPublicPrice($command->getPublicPrice());
        $materialPrice->setBigProjectPrice($command->getBigProjectPrice());

        $this->materialPriceRepository->save($materialPrice, true);

        return new MaterialPriceResponse($materialPrice);
    }
}
