<?php

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class AttachImageToSampleRequestDto extends BaseDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type("integer")]
    #[Assert\Positive]
    public int $imageId)
    {
    }
}