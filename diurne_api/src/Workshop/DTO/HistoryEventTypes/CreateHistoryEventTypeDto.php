<?php
declare(strict_types=1);

namespace App\Workshop\DTO\HistoryEventTypes;

use Symfony\Component\Validator\Constraints as Assert;

class CreateHistoryEventTypeDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public readonly string $name
    )
    {
    }
}