<?php

declare(strict_types=1);

namespace App\Workshop\DTO\WorkshopOrder;

use App\Common\Assert as CommonAssert;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWorkshopOrderRequestDto extends BaseDto
{ public function __construct(
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public readonly string $reference,

    #[Assert\NotBlank]
    #[Assert\Positive]
    public readonly int $image_command_id,

    #[Assert\NotBlank]
    #[Assert\Positive]
    public readonly int $workshop_information_id
) {
}
}