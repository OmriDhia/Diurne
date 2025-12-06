<?php

namespace App\Contremarque\Bus\Command\ImageCommandDesignerAssignment;

use App\Common\Bus\Command\Command;

class DeleteImageCommandDesignerAssignmentCommand implements Command
{
    public function __construct(private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}