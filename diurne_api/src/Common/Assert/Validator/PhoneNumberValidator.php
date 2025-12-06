<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\PhoneNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof PhoneNumber) {
            return;
        }
        if (!empty($value) && !preg_match('/^[+0-9. ()\/-]*$/', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
