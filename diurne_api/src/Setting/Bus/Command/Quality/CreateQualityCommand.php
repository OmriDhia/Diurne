<?php

// src/Setting/Bus/Command/Quality/CreateQualityCommand.php

namespace App\Setting\Bus\Command\Quality;

use App\Common\Bus\Command\Command;

class CreateQualityCommand implements Command
{
    public function __construct(public string $name, public ?string $weight, public ?string $velvetStandard, public ?array $description)
    {
    }
}
