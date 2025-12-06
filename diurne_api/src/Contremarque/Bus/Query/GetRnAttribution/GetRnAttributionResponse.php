<?php

namespace App\Contremarque\Bus\Query\GetRnAttribution;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;

class GetRnAttributionResponse implements QueryResponse
{
    /**
     * @param array<RnAttribution> $rnAttributions
     */
    public function __construct(
        public array $rnAttributions
    )
    {
    }

    public function toArray(): array
    {
        return array_map(
            fn(RnAttribution $rnAttribution) => $rnAttribution->toArray(),
            $this->rnAttributions
        );
    }
}