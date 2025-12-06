<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteHistoryEventCategory;

use App\Common\Bus\Command\Command;

class DeleteHistoryEventCategoryCommand implements Command
{

    /**
     * @param int $id
     */
    public function __construct(public readonly int $id)
    {
    }
}