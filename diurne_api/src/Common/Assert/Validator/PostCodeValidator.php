<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\PostCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PostCodeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof PostCode) {
            return;
        }
        if (!empty($value) && !preg_match('/^[a-zA-Z 0-9-]+$/', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
