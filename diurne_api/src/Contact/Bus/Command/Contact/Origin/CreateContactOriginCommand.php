<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact\Origin;

use App\Common\Bus\Command\Command;

class CreateContactOriginCommand implements Command
{
    public function __construct(
        private readonly string $label
    )
    {
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
