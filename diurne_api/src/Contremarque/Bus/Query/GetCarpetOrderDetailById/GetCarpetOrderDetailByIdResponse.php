<?php

namespace App\Contremarque\Bus\Query\GetCarpetOrderDetailById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetOrder\CarpetOrder;
use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;


class GetCarpetOrderDetailByIdResponse implements QueryResponse
{
    /**
     * @param CarpetOrderDetail $carpetOrder
     */
    public function __construct(
        private readonly CarpetOrderDetail $carpetOrderDetail
    )
    {
    }


    /**
     * @return carpetOrderDetail[]
     */
    public function toArray(): array
    {
        return $this->carpetOrderDetail->toArray();
    }
}