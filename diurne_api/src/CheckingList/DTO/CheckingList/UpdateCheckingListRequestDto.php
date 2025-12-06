<?php

namespace App\CheckingList\DTO\CheckingList;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCheckingListRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Positive]
        public ?int    $authorId = null,
        public ?string $date = null,
        public ?string $dateEndProd = null,
        public ?string $comment = null,
    )
    {
    }
}
