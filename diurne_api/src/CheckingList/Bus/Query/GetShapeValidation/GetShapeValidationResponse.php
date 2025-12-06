<?php

namespace App\CheckingList\Bus\Query\GetShapeValidation;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\ShapeValidation;

class GetShapeValidationResponse implements QueryResponse
{
    /** @var ShapeValidation[] */
    private array $list;

    /**
     * @param ShapeValidation[] $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    public function toArray(): array
    {
        return array_map(static fn(ShapeValidation $e) => $e->toArray(), $this->list);
    }
}
