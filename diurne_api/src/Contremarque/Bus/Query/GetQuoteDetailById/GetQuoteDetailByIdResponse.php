<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetQuoteDetailById;

use App\Common\Bus\Query\QueryResponse;

final class GetQuoteDetailByIdResponse implements QueryResponse
{
    /**
     * Constructor for GetQuoteDetailByIdResponse.
     *
     * @param array $quoteDetailData the quoteDetail data
     */
    public function __construct(
        public array $quoteDetailData
    ) {
    }

    public function toArray(): array
    {
        return [
            'quoteDetail' => $this->quoteDetailData,
        ];
    }
}
