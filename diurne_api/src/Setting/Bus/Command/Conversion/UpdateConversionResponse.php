<?php

namespace App\Setting\Bus\Command\Conversion;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Conversion;

class UpdateConversionResponse implements CommandResponse
{
    public function __construct(
        public Conversion $conversion
    ) {
    }

    /**
     * @return (DateTimeImmutable|array|int|null|string)[]
     *
     * @psalm-return array{id: int|null, coefficient: null|string, conversionDate: DateTimeImmutable|null, currency: array}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->conversion->getId(),
            'coefficient' => $this->conversion->getCoefficient(),
            'conversionDate' => $this->conversion->getConversionDate(),
            'currency' => $this->conversion->getCurrency()->toArray(),
        ];
    }
}
