<?php

declare(strict_types=1);

namespace App\Common\Validation;

/**
 * Class containing constants for various types of validation constraints.
 * These constants are used to specify the type of constraint being applied in validation error messages.
 */
class ConstraintKey
{
    public const REQUIRED = 'required';
    public const MIN_LENGTH = 'min_length';
    public const MAX_LENGTH = 'max_length';
    public const FORMAT = 'format';
    public const NOT_BLANK = 'not_blank';
    public const NOT_NULL = 'not_null';
    public const RANGE = 'range';
    public const INVALID = 'invalid';
}
