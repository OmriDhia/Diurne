<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\Address;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AddressValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        // Typecast constraint to Address
        if (!$constraint instanceof Address) {
            return;
        }
        if (!$constraint->allowEmpty && empty($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        } elseif (!empty($value) && !preg_match('/^[^!<>?=+@{}_$%]*$/u', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
