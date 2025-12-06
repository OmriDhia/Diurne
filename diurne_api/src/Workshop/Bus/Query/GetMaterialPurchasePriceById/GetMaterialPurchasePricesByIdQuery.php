<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetMaterialPurchasePriceById;

class GetMaterialPurchasePricesByIdQuery implements \App\Common\Bus\Query\Query
{
    /**
     * @param int $materialPurchasePricesId
     */
    public function __construct(
        public readonly int $materialPurchasePricesId
    )
    {
    }
}