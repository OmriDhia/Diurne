<?php

namespace App\CheckingList\Bus\Query\GetShapeValidationById;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\ShapeValidation;

class GetShapeValidationByIdResponse implements QueryResponse
{
    public function __construct(private readonly ShapeValidation $entity)
    {
    }

    public function toArray(): array
    {
        return $this->entity->toArray();
    }
}
