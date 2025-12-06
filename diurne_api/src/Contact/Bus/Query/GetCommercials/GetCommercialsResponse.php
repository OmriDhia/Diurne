<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetCommercials;

use App\Common\Bus\Query\QueryResponse;

final class GetCommercialsResponse implements QueryResponse
{
    /**
     * GetCommercialsResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $commercials
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{commercials: array}
     */
    public function toArray(): array
    {
        return [
            'commercials' => $this->commercials,
        ];
    }
}
