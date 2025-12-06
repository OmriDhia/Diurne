<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateRnAttribution;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;

class UpdateRnAttributionResponse implements CommandResponse
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