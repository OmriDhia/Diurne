<?php

namespace App\Setting\Bus\Command\TransportConditionLang;

use App\Common\Bus\Command\Command;

class UpdateTransportConditionLangCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly string $label,
        public readonly string $description,
        public readonly int $lang_id,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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
