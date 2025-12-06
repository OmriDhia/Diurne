<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\Date;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Date) {
            return;
        }
        if (!preg_match('/^([0-9]{4})-((0?[0-9])|(1[0-2]))-((0?[0-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
