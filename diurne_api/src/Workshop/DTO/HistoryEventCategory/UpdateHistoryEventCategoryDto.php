<?php

namespace App\Workshop\DTO\HistoryEventCategory;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateHistoryEventCategoryDto
{
    /**
     * @param string $name
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public readonly string $name
    )
    {
    }
}