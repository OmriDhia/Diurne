<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\CityName;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CityNameValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof CityName) {
            return;
        }
        if (!empty($value) && !preg_match('/^[^!<>;?=+@#"°{}_$%]*$/u', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
