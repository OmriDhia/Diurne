<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CheckSpecificationCoherence;

use App\Common\Bus\Query\QueryResponse;

final class CheckSpecificationCoherenceResponse implements QueryResponse
{
    public function __construct(
        private bool  $isCoherent,
        private array $differences
    )
    {
    }

    public function isCoherent(): bool
    {
        return $this->isCoherent;
    }

    public function getDifferences(): array
    {
        return $this->differences;
    }

    public function toArray(): array
    {
        return [
            'isCoherent' => $this->isCoherent,
            'differences' => $this->differences,
        ];
    }
}