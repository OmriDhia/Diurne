<?php

namespace App\CheckingList\Bus\Command\CreateLayersValidation;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\LayersValidation;

class CreateLayersValidationResponse implements CommandResponse
{
    public function __construct(private readonly LayersValidation $entity)
    {
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
