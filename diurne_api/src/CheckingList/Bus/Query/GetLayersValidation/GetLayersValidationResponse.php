<?php

namespace App\CheckingList\Bus\Query\GetLayersValidation;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\LayersValidation;

class GetLayersValidationResponse implements QueryResponse
{
    /** @var LayersValidation[] */
    private array $list;

    /**
     * @param LayersValidation[] $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    public function toArray(): array
    {
        return array_map(static fn(LayersValidation $e) => $e->toArray(), $this->list);
    }
}
