<?php

namespace App\Contremarque\Bus\Command\ProjectDi;

use DateTimeInterface;
use App\Common\Bus\Command\Command;

class UpdateProjectDiCommand implements Command
{
    public function __construct(
        public int $id,
        public ?string $format = null,
        public ?DateTimeInterface $deadline = null,
        public ?bool $transmitted_to_studio = null,
        public ?DateTimeInterface $transmition_date = null,
        public ?int $unit_id = null,
        public ?int $contremarque_id = null
    ) {
    }
}
