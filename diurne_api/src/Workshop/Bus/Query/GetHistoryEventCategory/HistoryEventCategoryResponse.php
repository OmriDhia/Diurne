<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventCategory;

use App\Common\Bus\Query\QueryResponse;
use App\Workshop\Entity\HistoryEventCategory;


class HistoryEventCategoryResponse implements QueryResponse
{
    /**
     * @param array $eventCategory
     */
    public function __construct(
        public array $eventCategory,
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(
            fn(HistoryEventCategory $eventCategory) => $eventCategory->toArray(),
            $this->eventCategory
        );
    }
}