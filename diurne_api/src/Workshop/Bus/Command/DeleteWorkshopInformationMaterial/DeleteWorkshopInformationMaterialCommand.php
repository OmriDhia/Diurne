<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopInformationMaterial;

use App\Common\Bus\Command\Command;

class DeleteWorkshopInformationMaterialCommand implements Command
{
    public function __construct(public int $id)
    {
    }
}
