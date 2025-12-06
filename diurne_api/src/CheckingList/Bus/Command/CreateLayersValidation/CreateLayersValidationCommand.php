<?php

namespace App\CheckingList\Bus\Command\CreateLayersValidation;

use App\Common\Bus\Command\Command;

class CreateLayersValidationCommand implements Command
{
    public function __construct(
        public readonly int $checkingListId,
        public readonly ?string $layerComment = null,
        public readonly ?bool $layerValidation = null,
    ) {
    }
}
