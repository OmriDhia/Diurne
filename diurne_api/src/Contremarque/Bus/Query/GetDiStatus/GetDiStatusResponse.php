<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDiStatus;

use App\Common\Bus\Query\QueryResponse;

final class GetDiStatusResponse implements QueryResponse
{
    public function __construct(public array $diStatuses)
    {
    }

    public function toArray(): array
    {
        return [
            'diStatuses' => $this->diStatuses,
        ];
    }
}
