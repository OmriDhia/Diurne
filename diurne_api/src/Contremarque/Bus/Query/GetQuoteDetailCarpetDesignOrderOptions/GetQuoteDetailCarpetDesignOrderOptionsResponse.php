<?php

namespace App\Contremarque\Bus\Query\GetQuoteDetailCarpetDesignOrderOptions;

class GetQuoteDetailCarpetDesignOrderOptionsResponse
{
    public function __construct(
        private readonly array $designOrderOptions
    ) {}

    public function toArray(): array
    {
        return array_map(
            fn($designOrderOption) => $designOrderOption->toArray(),
            $this->designOrderOptions
        );
    }
}
