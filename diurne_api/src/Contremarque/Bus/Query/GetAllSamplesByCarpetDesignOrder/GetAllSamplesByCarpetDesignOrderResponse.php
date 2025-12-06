<?php

namespace App\Contremarque\Bus\Query\GetAllSamplesByCarpetDesignOrder;

use App\Contremarque\Bus\Command\Sample\SampleResponse;
use App\Common\Bus\Query\QueryResponse;

class GetAllSamplesByCarpetDesignOrderResponse implements QueryResponse
{
    public function __construct(
        public readonly int $count,
        public readonly int $page,
        public readonly int $itemsPerPage,
        public readonly array $samples
    ) {}

    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'samples' => array_map(fn(SampleResponse $response) => $response->toArray(), $this->samples),
        ];
    }
}
