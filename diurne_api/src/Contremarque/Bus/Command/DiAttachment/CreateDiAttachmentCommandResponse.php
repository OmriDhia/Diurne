<?php

namespace App\Contremarque\Bus\Command\DiAttachment;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\DiAttachment;

class CreateDiAttachmentCommandResponse implements CommandResponse
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
