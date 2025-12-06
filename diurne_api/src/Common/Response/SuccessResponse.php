<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class SuccessResponse
 * This class extends the ApiResponse class and is used to create success responses.
 * It provides methods to create a standard get response and a standard create response.
 */
final class SuccessResponse extends ApiResponse
{
    public const DEFAULT_TYPE = 'success';
    public const DEFAULT_MESSAGE = 'success_message';
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;

    /**
     * Create a standard success response for created resources.
     *
     * @param mixed  $data       The data to be returned in the response
     * @param string $message    The message to be included in the response
     * @param int    $statusCode The HTTP status code for the response
     * @param string $type       The type of the response
     * @param array  $headers    Any additional headers to be added to the response
     *
     * @return JsonResponse The success response
     */
    public static function create(string $action, mixed $data = null, string $message = self::DEFAULT_MESSAGE, int $statusCode = self::HTTP_CREATED, string $type = self::DEFAULT_TYPE, array $headers = []): JsonResponse
    {
        return self::apiResponse($data, $action, $message, $statusCode, 'success', $type, false, $headers);
    }

    /**
     * Get a standard success response.
     *
     * @param mixed  $data       The data to be returned in the response
     * @param string $message    The message to be included in the response
     * @param int    $statusCode The HTTP status code for the response
     * @param string $type       The type of the response
     * @param array  $headers    Any additional headers to be added to the response
     *
     * @return JsonResponse The success response
     */
    public static function get(string $action, mixed $data = null, string $message = self::DEFAULT_MESSAGE, int $statusCode = self::HTTP_OK, string $type = self::DEFAULT_TYPE, array $headers = []): JsonResponse
    {
        return self::apiResponse($data, $action, $message, $statusCode, 'success', $type, false, $headers);
    }
}
