<?php

namespace App\CheckingList\Bus\Query\GetLayersValidationById;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\LayersValidation;

class GetLayersValidationByIdResponse implements QueryResponse
{
    public function __construct(private readonly LayersValidation $entity)
    {
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
