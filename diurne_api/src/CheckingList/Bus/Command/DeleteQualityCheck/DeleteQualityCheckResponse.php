<?php

namespace App\CheckingList\Bus\Command\DeleteQualityCheck;

use App\Common\Bus\Command\CommandResponse;

class DeleteQualityCheckResponse implements CommandResponse
{
    public function __construct(private readonly int $id)
    {
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
