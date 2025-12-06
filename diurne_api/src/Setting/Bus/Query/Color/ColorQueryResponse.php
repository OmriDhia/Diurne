<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Color;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Color;

class ColorQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|Color> $colors
     */
    public function __construct(private readonly array $colors, private readonly int $totalItems, private readonly ?int $page, private readonly int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $formattedColors = [];
        foreach ($this->colors as $color) {
            if ($color instanceof Color) {
                $formattedColors[] = [
                    'id' => $color->getId(),
                    'reference' => $color->getReference(),
                    'hexCode' => $color->getHexCode(),
                ];
            } else {
                $formattedColors[] = $color; // Already an array from cache
            }
        }

        return [
            'data' => $formattedColors,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
    }
}
