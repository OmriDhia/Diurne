<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetMeasurements;

use App\Common\Bus\Query\QueryResponse;

final class GetMeasurementsResponse implements QueryResponse
{
    public function __construct(public array $measurements)
    {
    }

    public function toArray(): array
    {
        return ['measurements' => $this->measurements];
    }
}
