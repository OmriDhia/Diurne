<?php

namespace App\Contremarque\Bus\Command\DeleteRnAttribution;


use App\Common\Bus\Command\CommandResponse;


class DeleteRnAttributionResponse implements CommandResponse
{
    /**
     * @param int $rnAttributionId
     */
    public function __construct(private readonly int $rnAttributionId)
    {
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {

        return ['id' => $this->rnAttributionId];
    }
}