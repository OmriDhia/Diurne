<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopInformation;

use App\Common\Bus\Command\Command;

class DeleteWorkshopInformationCommand implements Command
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