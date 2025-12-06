<?php

declare(strict_types=1);

namespace App\User\Bus\Query\GetGenders;

use App\Common\Bus\Query\QueryResponse;

final class GetGendersResponse implements QueryResponse
{
    /**
     * GetGendersResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $genders
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{genders: array}
     */
    public function toArray(): array
    {
        return [
            'genders' => $this->genders,
        ];
    }
}
