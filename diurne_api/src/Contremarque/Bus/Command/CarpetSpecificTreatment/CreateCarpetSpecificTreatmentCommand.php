<?php

namespace App\Contremarque\Bus\Command\CarpetSpecificTreatment;

use App\Common\Bus\Command\Command;

class CreateCarpetSpecificTreatmentCommand implements Command
{
    public function __construct(
        public readonly int $quoteDetailId,
        public readonly int $treatmentId
    ) {}

    public function getQuoteDetailId(): int
    {
        return $this->quoteDetailId;
    }

    public function getTreatmentId(): int
    {
        return $this->treatmentId;
    }
}
