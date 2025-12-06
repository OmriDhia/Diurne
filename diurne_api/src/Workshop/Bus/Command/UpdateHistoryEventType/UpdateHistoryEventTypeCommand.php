<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\UpdateHistoryEventType;


use App\Common\Bus\Command\Command;

class UpdateHistoryEventTypeCommand implements Command
{
    /**
     * @param int $id
     * @param string $name
     */
    public function __construct(
        public readonly int    $id,
        public readonly string $name
    )
    {
    }
}