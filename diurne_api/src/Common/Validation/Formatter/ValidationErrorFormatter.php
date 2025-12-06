<?php

declare(strict_types=1);

namespace App\Common\Validation\Formatter;

class ValidationErrorFormatter
{
    public const CONSTRAINT_KEY = 'constraint';
    public const FIELD_KEY = 'field';
    public const VALUE_KEY = 'value';

    /**
     * Formats a validation error.
     *
     * @return array the formatted validation error
     */
    public static function format(?string $constraintKey, string $field, mixed $value): array
    {
        return [
            self::CONSTRAINT_KEY => $constraintKey ?? 'undefined',
            self::FIELD_KEY => $field,
            self::VALUE_KEY => $value,
        ];
    }
}
