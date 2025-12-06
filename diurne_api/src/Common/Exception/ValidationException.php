<?php

declare(strict_types=1);

namespace App\Common\Exception;

use App\Common\Exception\Constant\ExceptionMessage;
use App\Common\Exception\Constant\ExceptionStatusCode;
use App\Common\Exception\Constant\ExceptionType;
use App\Common\Validation\Formatter\ValidationErrorFormatter;

class ValidationException extends ApiException
{
    private static ?self $instance = null;

    /**
     * Constructor allows previous usage while keeping violations optional.
     *
     * @param array $violations Initial violations (optional).
     */
    public function __construct(private array $violations = [])
    {
        parent::__construct(ExceptionMessage::VALIDATION, ExceptionStatusCode::VALIDATION_ERROR, ExceptionType::VALIDATION);
    }

    /**
     * Get or create a shared instance.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add a validation violation (static method).
     */
    public static function addViolation(string $constraint, string $field, mixed $value): void
    {
        self::getInstance()->violations[] = ValidationErrorFormatter::format($constraint, $field, $value);
    }

    /**
     * Throws an exception if there are violations.
     */
    public static function throwIfNeeded(): void
    {
        if (!empty(self::getInstance()->violations)) {
            throw self::getInstance();
        }
    }

    /**
     * Retrieve the stored violations.
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
