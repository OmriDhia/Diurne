<?php

declare(strict_types=1);

namespace App\Common\Assert;

use App\Common\Assert\Validator\IsSocialReasonRequiredValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class IsSocialReasonRequired extends Constraint
{
    public string $message = 'Social reason is required for non-Particulier customers.';

    public function validatedBy(): string
    {
        return IsSocialReasonRequiredValidator::class;
    }
}
