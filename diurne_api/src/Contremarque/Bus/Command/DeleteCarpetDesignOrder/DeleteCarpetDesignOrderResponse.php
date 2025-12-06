<?php

namespace App\Contremarque\Bus\Command\DeleteCarpetDesignOrder;


use App\Common\Bus\Command\CommandResponse;


class DeleteCarpetDesignOrderResponse implements CommandResponse
{
    /**
     * @param int $CarpetDesignOrderId
     */
    public function __construct(private readonly int $CarpetDesignOrderId)
    {
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {
        return ['id' => $this->CarpetDesignOrderId];
    }
}