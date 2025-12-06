<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\ImageType;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\ImageType;

class ImageTypesQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|ImageType> $imageTypes
     */
    public function __construct(private readonly array $imageTypes, private readonly int $totalItems, private readonly ?int $page, private readonly int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $formattedImageTypes = [];
        foreach ($this->imageTypes as $imageType) {
            if ($imageType instanceof ImageType) {
                $formattedImageTypes[] = [
                    'id' => $imageType->getId(),
                    'name' => $imageType->getName(),
                    'description' => $imageType->getDescription(),
                    'category' => $imageType->getCategory(),
                ];
            } else {
                $formattedImageTypes[] = $imageType; // Already an array from cache
            }
        }

        return [
            'data' => $formattedImageTypes,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
    }
}
