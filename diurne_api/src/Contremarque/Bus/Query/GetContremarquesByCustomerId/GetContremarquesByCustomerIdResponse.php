<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetContremarquesByCustomerId;

use App\Common\Bus\Query\QueryResponse;

final class GetContremarquesByCustomerIdResponse implements QueryResponse
{
    public function __construct(
        public $contremarques
    ) {
    }

    public function toArray(): array
    {
        $result = [];
        if (count($this->contremarques)) {
            foreach ($this->contremarques as $contremarque) {
                $result[] = $contremarque->toArray();
            }
        }

        return $result;
    }
}
