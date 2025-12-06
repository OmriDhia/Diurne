<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetImages;

use App\Common\Bus\Query\QueryResponse;

final class GetImagesQueryResponse implements QueryResponse
{
    public function __construct(public readonly array $images) {}

    public function toArray(): array
    {
        return [
            'images' => $this->images,
        ];
    }
}
