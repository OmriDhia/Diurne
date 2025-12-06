<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetRnAttributionById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;

class GetRnAttributionByIdResponse implements QueryResponse
{
    /**
     * @param RnAttribution $rnAttribution
     */
    public function __construct(
        public ?RnAttribution $rnAttribution,
        public ?RnAttribution $lastCanceled = null
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'active' => $this->rnAttribution?->toArray(),
            'lastCanceled' => $this->lastCanceled?->toArray()
        ];
    }
}