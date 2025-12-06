<?php

namespace App\Contremarque\Bus\Command\Sample;

use App\Common\Bus\Command\Command;

class DeleteSampleCommand implements Command
{
    public function __construct(
        public readonly int $id
    ) {}
}
