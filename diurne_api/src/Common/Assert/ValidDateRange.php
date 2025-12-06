<?php

namespace App\Common\Assert;

use App\Common\Assert\Validator\ValidDateRangeValidator;
use Symfony\Component\Validator\Constraint;

class ValidDateRange extends Constraint
{
    public string $message = 'The "From" date must be earlier than the "To" date, and both must be non-empty.';
    public string $mode = 'strict';
    public  bool $allowEmpty = false;

    // all configurable options must be passed to the constructor
    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }

    public function validatedBy(): string
    {
        return ValidDateRangeValidator::class;
    }
}
