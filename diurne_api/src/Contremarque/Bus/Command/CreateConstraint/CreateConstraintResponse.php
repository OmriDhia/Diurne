<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CreateConstraint;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CustomerConstraint;

class CreateConstraintResponse implements CommandResponse
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
