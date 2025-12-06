<?php

namespace App\CheckingList\Bus\Command\UpdateLayersValidation;

use App\Common\Bus\Command\Command;

class UpdateLayersValidationCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $layerComment = null,
        public readonly ?bool $layerValidation = null,
    ) {
    }
}
