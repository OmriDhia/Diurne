<?php

declare(strict_types=1);

namespace App\Contact\Bus\Query\GetOrigin;

use App\Common\Bus\Query\QueryResponse;

final class GetContactOriginResponse implements QueryResponse
{
    /**
     * GetIntermediaryTypesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $contactOrigin
    )
    {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{contactOrigin: array}
     */
    public function toArray(): array
    {
        return [
            'contactOrigin' => $this->contactOrigin,
        ];
    }
}
