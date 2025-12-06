<?php

namespace App\Common\Assert;

use Attribute;
use App\Common\Assert\Validator\LanguageExistsValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class LanguageExists extends Constraint
{
    public string $message = 'The language with ID {{ value }} does not exist.';

    public function validatedBy(): string
    {
        return LanguageExistsValidator::class;
    }
}
