<?php

namespace App\CheckingList\Bus\Command\UpdateShapeValidation;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\ShapeValidation;

class UpdateShapeValidationResponse implements CommandResponse
{
    public function __construct(private readonly ShapeValidation $entity)
    {
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
