<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\Command;

class DeleteAttachmentTypeCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
