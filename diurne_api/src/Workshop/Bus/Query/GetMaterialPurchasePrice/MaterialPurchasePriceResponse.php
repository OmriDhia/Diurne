<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetMaterialPurchasePrice;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Entity\MaterialPurchasePrice;


class MaterialPurchasePriceResponse implements QueryResponse
{
    /**
     * @param array $materialPurchasePrices
     */
    public function __construct(
        public array $materialPurchasePrices,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            fn(MaterialPurchasePrice $materialPurchasePrices) => $materialPurchasePrices->toArray(),
            $this->materialPurchasePrices
        );
    }
}