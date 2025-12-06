<?php

namespace App\Contremarque\Bus\Command\Location;

use App\Common\Bus\Command\Command;

class CreateLocationCommand implements Command
{
    public function __construct(
        private readonly int $contremarqueId,
        private readonly int $carpetTypeId,
        private readonly ?string $description,
        private readonly ?string $quote_processing_date,
        private readonly ?float $price_min,
        private readonly ?float $price_max,
        private readonly ?string $createdAt,
        private readonly ?bool $quote_processed = false,
    ) {
    }

    public function getContremarqueId(): int
    {
        return $this->contremarqueId;
    }

    public function getCarpetTypeId(): int
    {
        return $this->carpetTypeId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getQuoteProcessed(): ?bool
    {
        return $this->quote_processed;
    }

    public function getQuoteProcessingDate(): ?string
    {
        return $this->quote_processing_date;
    }

    public function getPriceMin(): ?float
    {
        return $this->price_min;
    }

    public function getPriceMax(): ?float
    {
        return $this->price_max;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
}
