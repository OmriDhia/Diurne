<?php

namespace App\CheckingList\Bus\Command\DeleteCheckingList;

use App\Common\Bus\Command\Command;

class DeleteCheckingListCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
