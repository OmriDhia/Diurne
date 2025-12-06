<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeCategoryByid;


use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventTypeCategory;

class HistoryEventTypeCategoryResponse implements QueryResponse
{
    /**
     * @param HistoryEventTypeCategory $category
     */
    public function __construct(
        public HistoryEventTypeCategory $category
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->category->toArray();
    }
}