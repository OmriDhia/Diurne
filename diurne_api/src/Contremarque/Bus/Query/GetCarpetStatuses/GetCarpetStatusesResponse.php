<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetStatuses;

use App\Common\Bus\Query\QueryResponse;

final class GetCarpetStatusesResponse implements QueryResponse
{
    public function __construct(public array $statuses)
    {
    }

    public function toArray(): array
    {
        return [
            'statuses' => $this->statuses,
        ];
    }
}
