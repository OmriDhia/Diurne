<?php
declare(strict_types=1);

namespace App\Workshop\DTO\HistoryEventTypeCategory;

use Symfony\Component\Validator\Constraints as Assert;

class CreateHistoryEventTypeCategoryDto
{
    /**
     * @param int $eventTypeId
     * @param int $eventCategoryId
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $eventTypeId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $eventCategoryId
    )
    {
    }
}