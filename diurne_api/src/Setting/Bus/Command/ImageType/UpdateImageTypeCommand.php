<?php

namespace App\Setting\Bus\Command\ImageType;

use App\Common\Bus\Command\Command;

class UpdateImageTypeCommand implements Command
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $name,
        public readonly ?string $description,
        public readonly ?string $category = null
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
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
