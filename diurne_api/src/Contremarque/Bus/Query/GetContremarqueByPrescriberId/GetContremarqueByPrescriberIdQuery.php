<?php

namespace App\Contremarque\Bus\Query\GetContremarqueByPrescriberId;

use App\Common\Bus\Query\Query;

class GetContremarqueByPrescriberIdQuery implements Query
{
    public function __construct(
        public readonly int $prescriberId
    ) {
    }
}
