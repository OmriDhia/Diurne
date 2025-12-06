<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\Name;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NameValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Name) {
            return;
        }
        if (!preg_match('/^[^0-9!<>,;?=+()@#"°{}_$%:¤|]*$/u', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
