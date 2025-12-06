<?php

namespace App\CheckingList\Bus\Command\DeleteQualityCheck;

use App\Common\Bus\Command\Command;

class DeleteQualityCheckCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
