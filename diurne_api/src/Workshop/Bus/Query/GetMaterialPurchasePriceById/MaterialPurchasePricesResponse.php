<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetMaterialPurchasePriceById;


use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\MaterialPurchasePrice;

class MaterialPurchasePricesResponse implements QueryResponse
{
    /**
     * @param MaterialPurchasePrice $materialPurchasePrices
     */
    public function __construct(
        public MaterialPurchasePrice $materialPurchasePrices
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->materialPurchasePrices->toArray();
    }
}