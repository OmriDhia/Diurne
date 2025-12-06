<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetCarpetByRnNumber;

use App\Common\Bus\Query\Query;

class GetCarpetByRnNumberQuery implements Query
{
    public function __construct(
        public int $carpetId
    ) {
    }

    public function getCarpetId(): int
    {
        return $this->carpetId;
    }
}
