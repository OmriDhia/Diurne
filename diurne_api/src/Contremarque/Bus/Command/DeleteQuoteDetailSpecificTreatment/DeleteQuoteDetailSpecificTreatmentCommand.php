<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteQuoteDetailSpecificTreatment;

use App\Common\Bus\Command\Command;

class DeleteQuoteDetailSpecificTreatmentCommand implements Command
{
    public function __construct(
        public int $specificTreatmentId,
    ) {}
}
