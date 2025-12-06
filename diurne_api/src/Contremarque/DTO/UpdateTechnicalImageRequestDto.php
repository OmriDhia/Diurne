<?php

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTechnicalImageRequestDto extends BaseDto
{
    #[Assert\Type('integer')]
    public ?int $imageCommandId = null;
    #[Assert\Type('integer')]
    public ?int $imageTypeId = null;
    #[Assert\Type('string')]
    public ?string $name = null;
    #[Assert\Type('integer')]
    public ?int $attachmentId = null;
}