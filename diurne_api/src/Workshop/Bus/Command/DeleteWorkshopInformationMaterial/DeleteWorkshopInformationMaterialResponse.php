<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Command\DeleteWorkshopInformationMaterial;

use App\Common\Bus\Command\CommandResponse;

class DeleteWorkshopInformationMaterialResponse implements CommandResponse
{
    public function __construct(public readonly int $id)
    {
    }
}
