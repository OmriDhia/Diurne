<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateHistoryEventTypeCategory;


use App\Common\Bus\Command\Command;

class UpdateHistoryEventTypeCategoryCommand implements Command
{
    /**
     * @param int $id
     * @param int $eventTypeId
     * @param int $eventCategoryId
     */
    public function __construct(
        public readonly int $id,
        public readonly int $eventTypeId,
        public readonly int $eventCategoryId
    )
    {
    }
}