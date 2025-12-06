<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDiAttachmentRequestDto
{
    public function __construct(#[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $attachmentId, #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $diId)
    {
    }

    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }

    public function getDiId(): int
    {
        return $this->diId;
    }
}
