<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\Command;

class UpdateAttachmentTypeCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
