<?php

namespace App\CheckingList\Bus\Command\DeleteLayersValidation;

use App\Common\Bus\Command\Command;

class DeleteLayersValidationCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
