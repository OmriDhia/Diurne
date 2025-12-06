<?php

namespace App\CheckingList\Bus\Command\DeleteLayersValidation;

use App\Common\Bus\Command\CommandResponse;

class DeleteLayersValidationResponse implements CommandResponse
{
    public function __construct(private readonly int $id)
    {
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
