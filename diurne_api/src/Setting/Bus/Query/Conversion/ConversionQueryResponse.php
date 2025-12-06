<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Conversion;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Conversion;

class ConversionQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $conversions)
    {
    }

    public function toArray(): array
    {
        /* @var Conversion $conversion */
        return array_map(fn($conversion) => [
            'id' => $conversion->getId(),
            'currency' => $conversion->getCurrency() ? $conversion->getCurrency()->toArray() : null,
            'conversionDate' => $conversion->getConversionDate() ? $conversion->getConversionDate()->format('Y-m-d H:i:s') : null,
            'coefficient' => $conversion->getCoefficient(),
        ], $this->conversions);
    }
}
