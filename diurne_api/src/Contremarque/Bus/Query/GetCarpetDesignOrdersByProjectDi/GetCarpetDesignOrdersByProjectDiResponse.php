<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrdersByProjectDi;

use App\Common\Bus\Query\QueryResponse;

final class GetCarpetDesignOrdersByProjectDiResponse implements QueryResponse
{
    public function __construct(public array $carpetDesignOrders)
    {
    }

    public function toArray(): array
    {
        return ['carpetDesignOrders' => $this->carpetDesignOrders];
    }
}
