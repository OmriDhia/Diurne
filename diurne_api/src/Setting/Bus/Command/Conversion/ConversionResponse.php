<?php

namespace App\Setting\Bus\Command\Conversion;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Conversion;

class ConversionResponse implements CommandResponse
{
    public function __construct(private readonly Conversion $conversion)
    {
    }

    public function toArray(): array
    {
        return $this->conversion->toArray();
    }
}
