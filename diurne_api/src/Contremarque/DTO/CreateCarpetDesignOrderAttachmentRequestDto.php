<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpetDesignOrderAttachmentRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $attachmentId, #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $carpetDesignOrderId)
    {
    }

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }

    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }
}
