<?php

namespace App\Contremarque\Bus\Command\DiAttachment;

use App\Common\Bus\Command\Command;

class UpdateDiAttachmentCommand implements Command
{
    public function __construct(private readonly int $diAttachmentId, private readonly int $attachmentId, private readonly int $diId)
    {
    }

    public function getDiAttachmentId(): int
    {
        return $this->diAttachmentId;
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
