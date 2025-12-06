<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopImage;

use App\Common\Bus\Command\Command;

class DeleteWorkshopImageCommand implements Command
{
    /**
     * @param int $id
     */
    public function __construct(
        public readonly int $id
    )
    {
    }
}