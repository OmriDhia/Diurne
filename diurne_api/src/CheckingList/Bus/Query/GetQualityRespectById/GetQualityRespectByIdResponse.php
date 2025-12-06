<?php

namespace App\CheckingList\Bus\Query\GetQualityRespectById;

use App\Common\Bus\Query\QueryResponse;
use App\CheckingList\Entity\QualityRespect;

class GetQualityRespectByIdResponse implements QueryResponse
{
    public function __construct(private QualityRespect $qualityRespect)
    {
    }

    public function toArray(): array
    {
        return $this->qualityRespect->toArray();
    }
}
