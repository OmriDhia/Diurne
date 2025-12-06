<?php

declare(strict_types=1);

namespace App\Workshop\DTO\WorkshopOrder;

use App\Common\Assert as CommonAssert;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateWorkshopOrderRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\Length(min: 1, max: 50)]
        public ?string $reference = null,

        #[Assert\Positive]
        public ?int    $image_command_id = null,

        #[Assert\Positive]
        public ?int    $workshop_information_id = null
    )
    {
    }
}