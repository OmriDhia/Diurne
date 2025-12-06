<?php

declare(strict_types=1);

namespace App\Common\Exception\Constant;

/**
 * ExceptionMessage class to hold constant messages for various exceptions.
 *
 * This class provides a centralized location for defining standard messages
 * associated with different types of exceptions. Using these constants ensures
 * consistency across the application when handling and reporting errors.
 */
class ExceptionMessage
{
    public const INTERNAL = 'internal_error';
    public const VALIDATION = 'validation';
    public const NOT_FOUND = 'not_found';
    public const DUPLICATE = 'duplicate';
    public const NOT_SUPPORTED = 'not_supported';
    public const INVALID_PAYLOAD = 'invalid_payload';
    public const INVALID_CREDENTIAL = 'invalid_credential';
}
