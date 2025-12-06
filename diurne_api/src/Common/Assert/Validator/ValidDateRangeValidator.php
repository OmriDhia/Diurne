<?php

namespace App\Common\Assert\Validator;

use DateTime;
use Exception;
use App\Common\Assert\ValidDateRange;
use App\Contact\Bus\Command\Commercial\AssignCommercialToCustomerCommand;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidDateRangeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidDateRange) {
            return;
        }
        if (!$value instanceof AssignCommercialToCustomerCommand) {
            return;
        }
        $fromDate = null;

        if (empty($fromDate) || empty($toDate)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

            return;
        }

        try {
            $from = new DateTime($fromDate);
            $to = new DateTime($toDate);
        } catch (Exception) {
            $this->context->buildViolation('Invalid date format.')
                ->addViolation();

            return;
        }

        if ($from >= $to) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
