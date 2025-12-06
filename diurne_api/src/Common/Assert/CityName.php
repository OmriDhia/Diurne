<?php

namespace App\Common\Assert;

use Attribute;
use App\Common\Assert\Validator\CityNameValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class CityName extends Constraint
{
    public string $message = 'Please enter a valid city name. Special characters like !<>;?=+@#"°{}_$% are not allowed.';

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
        return CityNameValidator::class;
    }
}
