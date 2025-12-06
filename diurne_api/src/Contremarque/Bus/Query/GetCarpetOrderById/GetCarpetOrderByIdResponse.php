<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrderById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetOrder\CarpetOrder;


class GetCarpetOrderByIdResponse implements QueryResponse
{
    /**
     * @param CarpetOrder $carpetOrder
     */
    public function __construct(
        private readonly CarpetOrder $carpetOrder
    )
    {
    }


    /**
     * @return CarpetOrder[]
     */
    public function toArray(): array
    {
        return $this->carpetOrder->toArray();
    }
}