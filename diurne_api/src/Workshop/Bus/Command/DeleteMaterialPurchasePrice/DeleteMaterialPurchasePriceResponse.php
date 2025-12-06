<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteMaterialPurchasePrice;

use App\Common\Bus\Command\CommandResponse;

class DeleteMaterialPurchasePriceResponse implements CommandResponse
{
    /**
     * @param int $materialPurchasePriceId
     */
    public function __construct(
        private readonly int $materialPurchasePriceId
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->materialPurchasePriceId
        ];
    }
}