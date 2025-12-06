<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteById;

use App\Common\Bus\Query\QueryResponse;

final class GetQuoteByIdResponse implements QueryResponse
{
    /**
     * Constructor for GetQuoteByIdResponse.
     *
     * @param array $quoteData the quote data
     * @param array|null $originalQuoteData the original quote data when the quote is a clone
     */
    public function __construct(
        public array  $quoteData,
        public ?array $originalQuoteData = null
    )
    {
    }

    public function toArray(): array
    {
        $quote = $this->quoteData;

        if (null !== $this->originalQuoteData) {
            $quote['originalQuote'] = $this->originalQuoteData;
        }

        return ['quote' => $quote];

    }
}
