<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventTypeCategory;

use App\Common\Bus\Command\Command;

class CreateHistoryEventTypeCategoryCommand implements Command
{
    /**
     * @param int $eventTypeId
     * @param int $eventCategoryId
     */
    public function __construct(
        public readonly int $eventTypeId,
        public readonly int $eventCategoryId
    )
    {
    }
}