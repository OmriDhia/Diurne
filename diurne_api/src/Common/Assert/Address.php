<?php

namespace App\Common\Assert;

use Attribute;
use App\Common\Assert\Validator\AddressValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Address extends Constraint
{
    public string $message = 'Please enter a valid address. Special characters like !<>?=+@{}_$% are not allowed.';
    public string $mode = 'strict';
    public bool $allowEmpty = false;

    // all configurable options must be passed to the constructor
    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }

    public function validatedBy(): string
    {
        return AddressValidator::class;
    }
}
