<?php

namespace App\Setting\Bus\Query\GetAllSpecialShape;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\SpecialShape;

class GetAllSpecialShapeResponse implements QueryResponse
{
    public function __construct(private readonly array $specialShapes)
    {
    }

    public function toArray(): array
    {
        return array_map(fn(SpecialShape $specialShape) => [
            'id' => $specialShape->getId(),
            'name' => $specialShape->getLabel(),
        ], $this->specialShapes);
    }
}
