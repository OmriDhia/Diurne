<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetImagesByCarpetDesignOrderId;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\Image;

final readonly class GetImagesByCarpetDesignOrderIdResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|Image> $images
     */
    public function __construct(private array $images, private int $totalItems, private ?int $page, private int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $formattedImages = [];
        foreach ($this->images as $image) {
            if ($image instanceof Image) {
                $formattedImages[] = $image->toArray();
            } else {
                $formattedImages[] = $image; // Already an array from cache
            }
        }

        return [
            'data' => $formattedImages,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
    }
}
