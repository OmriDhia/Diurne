<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetIntermediaryTypes;

use App\Common\Bus\Query\QueryResponse;

final class GetIntermediaryTypesResponse implements QueryResponse
{
    /**
     * GetIntermediaryTypesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $intermediaryTypes
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{intermediaryTypes: array}
     */
    public function toArray(): array
    {
        return [
            'intermediaryTypes' => $this->intermediaryTypes,
        ];
    }
}
