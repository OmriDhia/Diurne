<?php

// src/Setting/Bus/Command/CarpetCollection/CreateCarpetCollectionLangCommand.php

namespace App\Setting\Bus\Command\CarpetCollection;

use App\Common\Bus\Command\Command;

class CreateCarpetCollectionLangCommand implements Command
{
    public function __construct(
        private readonly int $carpetCollectionId,
        private readonly string $description,
        private readonly int $languageId
    ) {
    }

    public function getCarpetCollectionId(): int
    {
        return $this->carpetCollectionId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLanguageId(): int
    {
        return $this->languageId;
    }
}
