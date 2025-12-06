<?php

declare(strict_types=1);

namespace App\Workshop\Bus\Query\CheckWorkshopImageCommandCoherence;

use App\Common\Bus\Query\Query;

final class CheckWorkshopImageCommandCoherenceQuery implements Query
{
    public function __construct(
        private int $workshopOrderId,
        private int $imageCommandId
    ) {
    }

    public function getWorkshopOrderId(): int
    {
        return $this->workshopOrderId;
    }

    public function getImageCommandId(): int
    {
        return $this->imageCommandId;
    }
}
