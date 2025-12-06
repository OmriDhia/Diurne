<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopRnHistory;

use App\Common\Bus\Command\Command;

class DeleteWorkshopRnHistoryCommand implements Command
{
    public function __construct(
        public readonly int $id
    )
    {
    }
}