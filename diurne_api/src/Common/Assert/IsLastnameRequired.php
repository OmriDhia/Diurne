<?php

declare(strict_types=1);

namespace App\Common\Assert;

use App\Common\Assert\Validator\IsLastnameRequiredValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class IsLastnameRequired extends Constraint
{
    public string $message = 'Lastname is required for customer group "Particulier".';
    public function validatedBy(): string
    {
        return IsLastnameRequiredValidator::class;
    }
}
