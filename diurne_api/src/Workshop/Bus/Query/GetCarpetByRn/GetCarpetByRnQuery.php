<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetByRn;

use App\Common\Bus\Query\Query;

class GetCarpetByRnQuery implements Query
{
    public function __construct(
        public string $rnNumber
    ) {
    }

    public function getRnNumber(): string
    {
        return $this->rnNumber;
    }
}
