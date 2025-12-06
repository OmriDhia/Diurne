<?php

namespace App\CheckingList\Bus\Command\UpdateShapeValidation;

use App\Common\Bus\Command\Command;

class UpdateShapeValidationCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?bool $shapeRelevant = null,
        public readonly ?bool $shapeValidation = null,
        public readonly ?bool $shapeSeen = null,
        public readonly ?string $realWidth = null,
        public readonly ?string $realLength = null,
        public readonly ?string $surface = null,
        public readonly ?string $diagonalA = null,
        public readonly ?string $diagonalB = null,
        public readonly ?string $comment = null,
    ) {
    }
}
