<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteHistoryEventCategory;

use App\Common\Bus\Command\CommandResponse;

class DeleteHistoryEventCategoryResponse implements CommandResponse
{

    /**
     * @param int $eventCategoryId
     */
    public function __construct(
        private readonly int $eventCategoryId,

    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->eventCategoryId,
        ];
    }
}