<?php

declare(strict_types=1);

namespace App\MobileAppApi\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

final class QueryResponse extends JsonResponse
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data, int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }
}
