<?php

namespace App\CheckingList\Bus\Command\DeleteShapeValidation;

use App\Common\Bus\Command\Command;

class DeleteShapeValidationCommand implements Command
{
    public function __construct(public readonly int $id)
    {
    }
}
