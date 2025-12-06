<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeCategory;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventTypeCategory;

class HistoryEventTypeCategoryResponse implements QueryResponse
{
    /**
     * @param HistoryEventTypeCategory[] $categories
     */
    public function __construct(
        public array $categories
    )
    {
    }

    public function toArray(): array
    {
        return array_map(
            fn(HistoryEventTypeCategory $category) => $category->toArray(),
            $this->categories
        );
    }
}