<?php

declare(strict_types=1);

namespace App\Common\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiResponse
 * This abstract class extends the JsonResponse class from Symfony and is used to create API responses.
 * It provides a constructor to format the data to be returned in the response.
 */
abstract class ApiResponse
{
    /**
     * Get the JSON response.
     *
     * @param mixed  $data    the data to be returned in the response
     * @param string $message the message to be included in the response
     * @param string $status the HTTP status code for the response
     * @param string $type    the type of the response
     * @param bool   $error   indicates whether the response is an error response
     */
    protected static function apiResponse(
        mixed $data,
        string $action,
        string $message,
        int $statusCode,
        string $status,
        string $type,
        bool $error,
        array $headers
    ): JsonResponse {
        return new JsonResponse([
            'status' => $status,
            'type' => $type,
            'action' => $action,
            'message' => $message,
            'response' => $data,
            'error' => $error,
        ], $statusCode, $headers);
    }
}
