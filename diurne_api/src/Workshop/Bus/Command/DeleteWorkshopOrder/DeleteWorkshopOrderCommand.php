<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopOrder;

use App\Common\Bus\Command\Command;

class DeleteWorkshopOrderCommand implements Command
{
    /**
     * @param int $id WorkshopOrder ID to delete
     */
    public function __construct(
        public readonly int $id
    )
    {
    }
}