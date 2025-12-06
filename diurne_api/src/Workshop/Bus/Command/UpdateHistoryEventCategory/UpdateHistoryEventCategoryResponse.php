<?php

namespace App\Workshop\Bus\Command\UpdateHistoryEventCategory;

use App\Workshop\Entity\HistoryEventCategory;

class UpdateHistoryEventCategoryResponse
{
    /**
     * @param HistoryEventCategory $eventCategory
     */
    public function __construct(
        private readonly HistoryEventCategory $eventCategory
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->eventCategory->getId(),
            'name' => $this->eventCategory->getName(),
        ];
    }
}