<?php

namespace App\Common\Assert;

use Attribute;
use App\Common\Assert\Validator\PostCodeValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class PostCode extends Constraint
{
    public string $message = 'Please enter a valid postal code. It can include letters, numbers, and hyphens.';
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
        return PostCodeValidator::class;
    }
}
