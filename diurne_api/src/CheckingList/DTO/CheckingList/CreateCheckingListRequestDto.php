<?php

namespace App\CheckingList\DTO\CheckingList;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCheckingListRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public readonly int     $workshopOrderId,
        #[Assert\Positive]
        public readonly int     $authorId,
        public readonly ?string $date = null,
        public readonly ?string $dateEndProd = null,
        #[Assert\NotBlank]
        public readonly string  $comment,
    )
    {
    }
}
