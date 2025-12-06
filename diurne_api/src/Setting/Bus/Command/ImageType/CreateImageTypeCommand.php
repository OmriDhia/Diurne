<?php

namespace App\Setting\Bus\Command\ImageType;

use App\Common\Bus\Command\Command;

class CreateImageTypeCommand implements Command
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $description = null,
        public readonly ?string $category = null
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }
}
