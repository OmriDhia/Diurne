<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventCategoryById;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventCategory;

class HistoryEventCategoryResponse implements QueryResponse
{
    /**
     * @param HistoryEventCategory $eventCategory
     */
    public function __construct(
        public HistoryEventCategory $eventCategory
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->eventCategory->toArray();
    }
}