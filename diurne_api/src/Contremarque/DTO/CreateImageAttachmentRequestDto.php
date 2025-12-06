<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateImageAttachmentRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $imageId, #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $attachmentId)
    {
    }

    public function getImageId(): int
    {
        return $this->imageId;
    }

    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }
}
