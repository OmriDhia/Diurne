<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetByRn;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\Carpet;

class CarpetResponse implements QueryResponse
{
    public function __construct(
        public Carpet $carpet
    ) {
    }

    public function toArray(): array
    {
        return $this->carpet->toArray();
    }
}
