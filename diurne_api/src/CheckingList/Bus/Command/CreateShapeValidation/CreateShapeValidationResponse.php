<?php

namespace App\CheckingList\Bus\Command\CreateShapeValidation;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\ShapeValidation;

class CreateShapeValidationResponse implements CommandResponse
{
    public function __construct(private readonly ShapeValidation $entity)
    {
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
