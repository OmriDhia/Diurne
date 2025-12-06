<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact\Origin;

use App\Common\Bus\Command\Command;

class DeleteContactOriginCommand implements Command
{
    public function __construct(
        private readonly int $originId,
    )
    {
    }

    public function getOriginId(): int
    {
        return $this->originId;
    }


}
