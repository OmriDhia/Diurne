<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllModels;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Model;

class ModelQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|Model> $models
     */
    public function __construct(private readonly array $models, private readonly int $totalItems, private readonly ?int $page, private readonly int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $modelsData = [];
        foreach ($this->models as $model) {
            if ($model instanceof Model) {
                $modelsData[] = $model->toArray();
            } else {
                $modelsData[] = $model; // Already an array from cache
            }
        }

        return [
            'data' => $modelsData,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
    }
}
