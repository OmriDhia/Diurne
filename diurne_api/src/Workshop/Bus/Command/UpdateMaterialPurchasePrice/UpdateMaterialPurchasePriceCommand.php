<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateMaterialPurchasePrice;

use App\Common\Bus\Command\Command;

class UpdateMaterialPurchasePriceCommand implements Command
{
    /**
     * @param int $id
     * @param int $materialId
     * @param string $price
     * @param int $productionOrderId
     * @param int $workshopInformationId
     */
    public function __construct(
        public int             $id,
        public readonly int    $materialId,
        public readonly string $price,
        public readonly int    $productionOrderId,
        public readonly int    $workshopInformationId
    )
    {
    }
}