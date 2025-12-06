<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCarpetDesignOrderAttachmentRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $id, #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $carpetDesignOrderId, #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private readonly int $attachmentId)
    {
    }

    public function getId(): int
    {
        return $this->id;
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
