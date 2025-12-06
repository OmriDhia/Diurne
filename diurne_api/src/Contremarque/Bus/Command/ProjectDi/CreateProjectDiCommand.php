<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\ProjectDi;

use DateTimeInterface;
use App\Common\Bus\Command\Command;

class CreateProjectDiCommand implements Command
{
    public function __construct(
        public string $format,
        public ?DateTimeInterface $deadline,
        public ?bool $transmitted_to_studio,
        public ?DateTimeInterface $transmition_date,
        public ?int $unit_id,
        public ?int $contremarque_id
    ) {}
}
