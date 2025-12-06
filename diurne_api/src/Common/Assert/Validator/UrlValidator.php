<?php

namespace App\Common\Assert\Validator;

use App\Common\Assert\Url;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UrlValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Url) {
            return;
        }
        if (!empty($value) && !preg_match('/^[~:#,$%&_=\(\)\.\? \+\-@\/a-zA-Z0-9\pL\pS-]+$/u', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
