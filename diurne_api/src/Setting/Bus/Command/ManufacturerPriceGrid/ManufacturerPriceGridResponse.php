<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\ManufacturerPriceGrid;

class ManufacturerPriceGridResponse implements CommandResponse
{
    public function __construct(private readonly ManufacturerPriceGrid $priceGrid) {}

    public function toArray(): array
    {
        $manufacturer = $this->priceGrid->getManufacturer();
        $quality = $this->priceGrid->getQuality();

        return [
            'id' => $this->priceGrid->getId(),
            'manufacturer' => $manufacturer ? [
                'id' => $manufacturer->getId(),
                'name' => $manufacturer->getName(),
                'company' => $manufacturer->getCompany(),
            ] : null,
            'quality' => $quality ? [
                'id' => $quality->getId(),
                'name' => $quality->getName(),
                'weight' => $quality->getWeight(),
            ] : null,
            'tarifGroup' => $this->priceGrid->getTarifGroup()?->toArray(),
            'tariffGrid' => $this->priceGrid->getTariffGrid(),
            'knots' => $this->priceGrid->getKnots(),
            'special' => $this->priceGrid->getSpecial(),
            'standardVelours' => $this->priceGrid->getStandardVelours(),
            'prices' => $this->priceGrid->getManufacturerPrices()
                ->map(fn($price) => $price->toArray())
                ->toArray(),
            'isActive' => $this->priceGrid->isActive(),
            'createdAt' => $this->priceGrid->getCreatedAt()?->format(\DateTimeInterface::ATOM),
            'updatedAt' => $this->priceGrid->getUpdatedAt()?->format(\DateTimeInterface::ATOM),
        ];
    }

    public function getPriceGrid(): ManufacturerPriceGrid
    {
        return $this->priceGrid;
    }
}
