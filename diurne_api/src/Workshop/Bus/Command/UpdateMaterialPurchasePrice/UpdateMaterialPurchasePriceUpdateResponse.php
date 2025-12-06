<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateMaterialPurchasePrice;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\MaterialPurchasePrice;
use App\Workshop\Entity\WorkshopInformation;

class UpdateMaterialPurchasePriceUpdateResponse implements CommandResponse
{
    /**
     * @param MaterialPurchasePrice $materialPurchasePrice
     */
    public function __construct(
        private readonly MaterialPurchasePrice $materialPurchasePrice,

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
            ]
        ];
    }
}