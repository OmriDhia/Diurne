<?php

namespace App\Setting\Bus\Query\Police;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Police;

class PoliceQueryResponse implements QueryResponse
{
    public function __construct(
        /**
         * @var Police[]
         */
        private readonly array $policeEntities
    )
    {
    }

    public function toArray(): array
    {
        $response = [];
        foreach ($this->policeEntities as $policeEntity) {
            $response[] = [
                'id' => $policeEntity->getId(),
                'label' => $policeEntity->getLabel(),
            ];
        }

        return $response;
    }
}
