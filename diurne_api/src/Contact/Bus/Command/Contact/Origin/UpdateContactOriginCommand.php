<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact\Origin;

use App\Common\Bus\Command\Command;

class UpdateContactOriginCommand implements Command
{
    public function __construct(
        private readonly int    $originId,
        private readonly string $label
    )
    {
    }

    public function getOriginId(): int
    {
        return $this->originId;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
