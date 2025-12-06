<?php

namespace App\CheckingList\Bus\Command\CreateShapeValidation;

use App\Common\Bus\Command\Command;

class CreateShapeValidationCommand implements Command
{
    public function __construct(
        public readonly int $checkingListId,
        public readonly ?bool $shapeValidation = null,
        public readonly string $realWidth,
        public readonly string $realLength,
        public readonly string $surface,
        public readonly ?string $diagonalA = null,
        public readonly ?string $diagonalB = null,
        public readonly ?string $comment = null,
    ) {
    }
}
