<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Color;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Color;

final readonly class GetByIdColorResponse implements QueryResponse
{
    public function __construct(private ?Color $color)
    {
    }

    public function toArray(): array
    {
        return $this->color ? [
            'id' => $this->color->getId(),
            'reference' => $this->color->getReference(),
            'hexCode' => $this->color->getHexCode(),
        ] : [];
    }
}
