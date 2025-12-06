<?php

namespace App\Contremarque\Bus\Command\CreateRnAttribution;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;

class CreateRnAttributionResponse implements CommandResponse
{
    /**
     * @param RnAttribution $rnAttribution
     */
    public function __construct(
        public RnAttribution $rnAttribution
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->rnAttribution->toArray();
    }
}