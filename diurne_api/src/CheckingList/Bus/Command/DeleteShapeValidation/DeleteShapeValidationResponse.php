<?php

namespace App\CheckingList\Bus\Command\DeleteShapeValidation;

use App\Common\Bus\Command\CommandResponse;

class DeleteShapeValidationResponse implements CommandResponse
{
    public function __construct(private readonly int $id)
    {
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
