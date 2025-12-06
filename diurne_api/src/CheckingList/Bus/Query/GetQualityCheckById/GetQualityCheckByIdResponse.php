<?php

namespace App\CheckingList\Bus\Query\GetQualityCheckById;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\QualityCheck;

class GetQualityCheckByIdResponse implements QueryResponse
{
    public function __construct(private QualityCheck $qualityCheck)
    {
    }

    public function toArray(): array
    {
        return $this->qualityCheck->toArray();
    }
}
