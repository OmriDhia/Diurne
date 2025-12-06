<?php

namespace App\Contremarque\Bus\Command\DiAttachment;

use App\Contremarque\Entity\DiAttachment;

class UpdateDiAttachmentResponse
{
    public function __construct(private readonly DiAttachment $diAttachment)
    {
    }

    public function getDiAttachmentId(): int|null
    {
        return $this->diAttachment->getId();
    }

    public function toArray(): void
    {
        $this->diAttachment->toArray();
    }
}
