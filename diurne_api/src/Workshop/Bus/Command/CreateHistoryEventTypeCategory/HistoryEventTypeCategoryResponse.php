<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventTypeCategory;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\HistoryEventTypeCategory;

class HistoryEventTypeCategoryResponse implements CommandResponse
{
    /**
     * @param HistoryEventTypeCategory $category
     */
    public function __construct(
        private readonly HistoryEventTypeCategory $category
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