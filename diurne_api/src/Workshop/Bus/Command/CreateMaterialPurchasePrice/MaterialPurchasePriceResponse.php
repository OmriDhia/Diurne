<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateMaterialPurchasePrice;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\MaterialPurchasePrice;
use App\Workshop\Entity\WorkshopInformation;

class MaterialPurchasePriceResponse implements CommandResponse
{
    /**
     * @param MaterialPurchasePrice $materialPurchasePrice
     * @param WorkshopInformation $workshopInformation
     */
    public function __construct(
        private readonly MaterialPurchasePrice $materialPurchasePrice,
        private readonly WorkshopInformation   $workshopInformation
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'materialPurchasePrice' => [
                'id' => $this->materialPurchasePrice->getId(),
                'materialId' => $this->materialPurchasePrice->getMaterialId(),
                'price' => $this->materialPurchasePrice->getPrice(),
                'productionOrderId' => $this->materialPurchasePrice->getWorkshopOrder()?->getId()
            ],
            'workshopInformation' => [
                'id' => $this->workshopInformation->getId(),
            ]
        ];
    }
}