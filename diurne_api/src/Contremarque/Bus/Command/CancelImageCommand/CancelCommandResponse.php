<?php

namespace App\Contremarque\Bus\Command\CancelImageCommand;


use App\Common\Bus\Command\CommandResponse;


class CancelCommandResponse implements CommandResponse
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