<?php

namespace App\Setting\Bus\Command\MaterialPrice;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Repository\MaterialPriceRepository;
use App\Setting\Repository\MaterialRepository;
use Doctrine\ORM\Exception\ORMException;

class UpdateMaterialPriceCommandHandler implements CommandHandler
{
    public function __construct(private readonly MaterialPriceRepository $materialPriceRepository, private readonly MaterialRepository $materialRepository)
    {
    }

    /**
     * @throws ORMException
     */
    public function __invoke(UpdateMaterialPriceCommand $command): MaterialPriceResponse
    {
        $materialPrice = $this->materialPriceRepository->find((int) $command->getId());

        if (null === $materialPrice) {
            throw new ResourceNotFoundException();
        }

        $material = $this->materialRepository->find($command->getMaterialId());
        if (!$material) {
            throw new InvalidArgumentException('Material not found');
        }

        $materialPrice->setPublicPrice($command->getPublicPrice());
        $materialPrice->setBigProjectPrice($command->getBigProjectPrice());
        $materialPrice->setMaterial($material);

        $this->materialPriceRepository->save($materialPrice, true);

        return new MaterialPriceResponse($materialPrice);
    }
}
