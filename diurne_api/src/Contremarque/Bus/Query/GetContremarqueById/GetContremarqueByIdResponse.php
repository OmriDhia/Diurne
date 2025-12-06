<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetContremarqueById;

use App\Common\Bus\Query\QueryResponse;

final class GetContremarqueByIdResponse implements QueryResponse
{
    /**
     * Constructor for GetContremarqueByIdResponse.
     *
     * @param array $contremarqueData the contremarque data
     */
    public function __construct(
        public array $contremarqueData
    ) {
    }

    public function toArray(): array
    {
        return [
            'contremarque' => $this->contremarqueData,
        ];
    }
}
