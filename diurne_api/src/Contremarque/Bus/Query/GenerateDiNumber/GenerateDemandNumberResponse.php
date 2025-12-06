<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GenerateDiNumber;

use App\Common\Bus\Query\QueryResponse;

final readonly class GenerateDemandNumberResponse implements QueryResponse
{
    public function __construct(private string $demandNumber)
    {
    }

    public function getDemandNumber(): string
    {
        return $this->demandNumber;
    }

    public function toArray(): array
    {
        return [
            'demandNumber' => $this->getDemandNumber(),
        ];
    }
}
