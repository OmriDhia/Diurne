<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteHistoryEventTypeCategory;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\HistoryEventTypeCategory;

class DeleteHistoryEventTypeCategoryResponse implements CommandResponse
{
    /**
     * @param int $deletedCategoryId
     */
    public function __construct(
        private readonly int $deletedCategoryId
    )
    {
    }

    /**
     * @return HistoryEventTypeCategory[]
     */
    public function toArray(): array
    {
        return [
            'id' => $this->deletedCategoryId,
        ];
    }
}