<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetNomenclatures;

use App\Common\Bus\Query\QueryResponse;

final class EventNomenclaturesResponse implements QueryResponse
{
    public function __construct(
        public array $nomenclatures,
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{nomenclatures: array}
     */
    public function toArray(): array
    {
        return [
            'nomenclatures' => $this->nomenclatures,
        ];
    }
}
