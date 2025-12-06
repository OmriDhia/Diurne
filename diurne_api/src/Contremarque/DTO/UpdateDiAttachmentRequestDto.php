<?php

namespace App\Contremarque\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateDiAttachmentRequestDto
{
    public function __construct(#[Assert\NotNull]
    private readonly int $attachmentId, #[Assert\NotNull]
    private readonly int $diId)
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
