<?php

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteManufacturerPriceGridCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID cannot be empty.')]
        #[Assert\Positive(message: 'ID must be positive.')]
        public readonly int $id
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
}
