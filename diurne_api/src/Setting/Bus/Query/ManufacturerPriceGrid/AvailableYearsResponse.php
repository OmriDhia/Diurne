<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ManufacturerPriceGrid;

use App\Common\Bus\Query\QueryResponse;

class AvailableYearsResponse implements QueryResponse
{
    public function __construct(private readonly array $tarifGroups) {}

    public function toArray(): array
    {
        return [
            'tarifGroups' => array_map(
                static fn($group) => method_exists($group, 'toArray') ? $group->toArray() : $group,
                $this->tarifGroups
            ),
            'count' => count($this->tarifGroups)
        ];
    }

    public function getTarifGroups(): array
    {
        return $this->tarifGroups;
    }
}
