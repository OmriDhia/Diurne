<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetDiscountRules;

use App\Common\Bus\Query\QueryResponse;

final class GetDiscountRulesResponse implements QueryResponse
{
    /**
     * GetCountriesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $discountRules
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{discountRules: array}
     */
    public function toArray(): array
    {
        return [
            'discountRules' => $this->discountRules,
        ];
    }
}
