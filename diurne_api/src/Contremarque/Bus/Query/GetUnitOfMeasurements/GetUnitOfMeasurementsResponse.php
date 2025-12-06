<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetUnitOfMeasurements;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\UnitOfMeasurement;

final readonly class GetUnitOfMeasurementsResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|UnitOfMeasurement> $units
     */
    public function __construct(private array $units, private int $totalItems, private ?int $page, private int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $formattedUnits = [];
        foreach ($this->units as $unit) {
            if ($unit instanceof UnitOfMeasurement) {
                $formattedUnits[] = [
                    'id' => $unit->getId(),
                    'name' => $unit->getName(),
                    'abbreviation' => $unit->getAbbreviation(),
                ];
            } else {
                $formattedUnits[] = $unit; // Already an array from cache
            }
        }

        return [
            'data' => $formattedUnits,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
    }
}
