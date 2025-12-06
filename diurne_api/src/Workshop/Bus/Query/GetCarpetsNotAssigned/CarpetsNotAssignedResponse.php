<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetsNotAssigned;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\Carpet;

final class CarpetsNotAssignedResponse implements QueryResponse
{
    /**
     * @param Carpet[] $carpets
     */
    public function __construct(
        public array $carpets,
    ) {
    }

    public function toArray(): array
    {
        return array_map(
            static fn (Carpet $carpet) => $carpet->toArray(),
            $this->carpets
        );
    }
}
