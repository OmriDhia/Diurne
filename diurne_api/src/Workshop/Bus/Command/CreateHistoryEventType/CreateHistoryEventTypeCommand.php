<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventType;


use App\Common\Bus\Command\Command;

class CreateHistoryEventTypeCommand implements Command
{
    /**
     * @param string $name
     */
    public function __construct(
        public readonly string $name
    )
    {
    }
}