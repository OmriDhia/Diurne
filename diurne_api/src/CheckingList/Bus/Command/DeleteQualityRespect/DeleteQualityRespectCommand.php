<?php

namespace App\CheckingList\Bus\Command\DeleteQualityRespect;

use App\Common\Bus\Command\Command;

class DeleteQualityRespectCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
