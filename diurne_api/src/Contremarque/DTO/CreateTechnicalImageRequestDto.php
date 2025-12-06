<?php

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTechnicalImageRequestDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $imageCommandId;
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $imageTypeId;
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $name;
    #[Assert\Type('integer')]
    public int $attachmentId;
}