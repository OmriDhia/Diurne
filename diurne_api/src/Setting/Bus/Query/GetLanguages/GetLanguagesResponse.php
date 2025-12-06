<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetLanguages;

use App\Common\Bus\Query\QueryResponse;

final class GetLanguagesResponse implements QueryResponse
{
    /**
     * GetLanguagesResponse constructor.
     *
     * @param $
     */
    public function __construct(
        public array $languages
    ) {
    }

    /**
     * @return array[]
     *
     * @psalm-return array{languages: array}
     */
    public function toArray(): array
    {
        return [
            'languages' => $this->languages,
        ];
    }
}
