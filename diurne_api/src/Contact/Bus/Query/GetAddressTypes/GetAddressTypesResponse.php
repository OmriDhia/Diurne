<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetAddressTypes;

use App\Common\Bus\Query\QueryResponse;

final class GetAddressTypesResponse implements QueryResponse
{
    /**
     * GetAddressTypesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $addressTypes
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{addressTypes: array}
     */
    public function toArray(): array
    {
        return [
            'addressTypes' => $this->addressTypes,
        ];
    }
}
