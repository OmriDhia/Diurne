<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\Price;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PriceValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Price) {
            return;
        }
        if (!empty($value) && !preg_match('/^[0-9]{1,10}(\.[0-9]{1,9})?$/', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
