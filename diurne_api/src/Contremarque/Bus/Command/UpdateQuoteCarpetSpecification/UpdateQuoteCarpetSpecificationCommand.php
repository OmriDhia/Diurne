<?php

namespace App\Contremarque\Bus\Command\UpdateQuoteCarpetSpecification;

use App\Common\Bus\Command\Command;
use App\Contremarque\DTO\UpdateCarpetSpecificationDTO;

class UpdateQuoteCarpetSpecificationCommand implements Command
{
    public ?int $id = null;

    public function __construct(
        public int $quoteDetailId,
        public UpdateCarpetSpecificationDTO $carpetSpecificationDTO
    ) {}

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
