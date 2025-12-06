<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDefaultComposition;

use App\Common\Bus\Query\QueryResponse;

final class GetDefaultCompositionQueryResponse implements QueryResponse
{
    public function __construct(public array $defaultComposition)
    {
    }

    public function toArray(): array
    {
        return ['defaultComposition' => $this->defaultComposition];
    }
}
