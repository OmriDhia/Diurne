<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetCountries;

use App\Common\Bus\Query\QueryResponse;

final class GetCountriesResponse implements QueryResponse
{
    /**
     * GetCountriesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $countries
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{countries: array}
     */
    public function toArray(): array
    {
        return [
            'countries' => $this->countries,
        ];
    }
}
