<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetMaterialPurchasePriceByWorkshopOrderId;

use App\Common\Bus\Query\Query;

final readonly class GetMaterialPurchasePricesByWorkshopOrderIdQuery implements Query
{
    public function __construct(public int $workshopOrderId)
    {
    }
}
