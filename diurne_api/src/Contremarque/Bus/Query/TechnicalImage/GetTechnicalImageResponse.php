<?php

namespace App\Contremarque\Bus\Query\TechnicalImage;

use App\Common\Bus\Query\QueryResponse;

class GetTechnicalImageResponse implements QueryResponse
{
    public function __construct(public array $technicalImage)
    {
    }

    public function toArray(): array
    {
        return ['technicalImage' => $this->technicalImage];
    }
}