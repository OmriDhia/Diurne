<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\CreateHistoryEventCategory;


use App\Common\Bus\Command\Command;

class CreateHistoryEventCategoryCommand implements Command
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