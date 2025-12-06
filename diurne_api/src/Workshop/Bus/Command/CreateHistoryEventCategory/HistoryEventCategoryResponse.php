<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventCategory;

use App\Common\Bus\Command\CommandResponse;
use App\Workshop\Entity\HistoryEventCategory;


class HistoryEventCategoryResponse implements CommandResponse
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