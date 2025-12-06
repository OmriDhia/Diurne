<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpets;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\Carpet;

class CarpetsResponse implements QueryResponse
{
    /**
     * @param array $carpets
     */
    public function __construct(
        public array $carpets,
    )
    {
    }

    public function toArray(): array
    {
        return array_map(
            fn(Carpet $carpets) => $carpets->toArray(),
            $this->carpets
        );
    }
}