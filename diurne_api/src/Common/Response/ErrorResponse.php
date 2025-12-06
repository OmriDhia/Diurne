<?php

declare(strict_types=1);

namespace App\Common\Response;

use App\Common\Exception\Constant\ExceptionStatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ErrorResponse
 * This class extends the ApiResponse class and is used to create error responses.
 * It provides a constructor to format the data to be returned in the response.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ErrorResponse extends ApiResponse
{
    public const DEFAULT_TYPE = 'error';
    public const DEFAULT_MESSAGE = 'error_message';

    /**
     * Get the JSON response for an error.
     *
     * @param string     $action     The action identifier for the response
     * @param mixed|null $data       The data to be returned in the response
     * @param string     $message    The message to be included in the response
     * @param string     $type       The type of the response
     * @param int        $statusCode The HTTP status code for the response
     * @param array      $headers    Any additional headers to be added to the response
     */
    public static function response(
        string $action,
        mixed $data = null,
        string $message = self::DEFAULT_MESSAGE,
        string $type = self::DEFAULT_TYPE,
        int $statusCode = ExceptionStatusCode::INTERNAL_ERROR,
        array $headers = []
    ): JsonResponse {
        return self::apiResponse($data, $action, $message, $statusCode, 'fail', $type, true, $headers);
    }
}
