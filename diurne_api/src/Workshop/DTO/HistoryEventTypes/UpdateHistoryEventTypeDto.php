<?php

namespace App\Workshop\DTO\HistoryEventTypes;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateHistoryEventTypeDto
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