<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateConstraint;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CustomerConstraint;

class UpdateConstraintResponse implements CommandResponse
{
    public function __construct(private readonly CustomerConstraint $constraint)
    {
    }

    public function getConstraint(): CustomerConstraint
    {
        return $this->constraint;
    }

    public function toArray(): array
    {
        return $this->constraint->toArray();
    }
}
