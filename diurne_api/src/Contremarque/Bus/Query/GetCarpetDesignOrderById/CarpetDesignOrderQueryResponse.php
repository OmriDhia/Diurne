<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrderById;

use App\Common\Bus\Query\QueryResponse;

class CarpetDesignOrderQueryResponse implements QueryResponse
{
    /**
     * @param array<string, mixed> $carpetDesignOrderData
     */
    public function __construct(private readonly array $carpetDesignOrderData)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge($this->carpetDesignOrderData, [
            'hasImageCommand' => $this->carpetDesignOrderData['hasImageCommand'] ?? false,
            'imageCommandIsCanceled' => $this->carpetDesignOrderData['imageCommandIsCanceled'] ?? false,
        ]);
    }
}
