<?php

namespace App\CheckingList\Bus\Command\DeleteQualityRespect;

use App\Common\Bus\Command\CommandResponse;

class DeleteQualityRespectResponse implements CommandResponse
{
    public function __construct(private readonly int $id)
    {
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
