<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\Command;

class CreateAttachmentTypeCommand implements Command
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
