<?php
declare(strict_types=1);

namespace App\Workshop\DTO\HistoryEventCategory;

use Symfony\Component\Validator\Constraints as Assert;

class CreateHistoryEventCategoryDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 50)]
        public readonly string $name
    )
    {
    }
}