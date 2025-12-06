<?php

namespace App\Setting\Bus\Command\TransportConditionLang;

use App\Common\Bus\Command\Command;

class CreateTransportConditionLangCommand implements Command
{
    public function __construct(
        public readonly int $transport_condition_id,
        public readonly string $label,
        public readonly string $description,
        public readonly int $lang_id,
    ) {
    }

    public function getTransportConditionId(): int
    {
        return $this->transport_condition_id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLangId(): int
    {
        return $this->lang_id;
    }
}
