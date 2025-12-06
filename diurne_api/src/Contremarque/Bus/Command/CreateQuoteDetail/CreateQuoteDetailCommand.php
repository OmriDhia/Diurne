<?php

namespace App\Contremarque\Bus\Command\CreateQuoteDetail;

use DateTimeImmutable;
use App\Common\Bus\Command\Command;

class CreateQuoteDetailCommand implements Command
{
    public function __construct(
        public int $quoteId,
        public int $locationId,
        public int $tarifId,
        public int $estimatedDeliveryTime,
        public bool $isValidated,
        public ?string $totalPriceRate,
        public ?DateTimeImmutable $validatedAt,
        public ?int $wantedQuantity,
        public ?string $reference,
        public ?bool $applyLargeProjectRate,
        public ?bool $applyProposedDiscount,
        public ?string $proposedDiscountRate,
        public ?bool $calculateFromTotalExcludingTax,
        public ?int $currencyId,
        public ?bool $inStockCarpet,
        public ?string $comment,
        public ?string $rn,
        public readonly ?array $specificTreatmentIds = null,
    ) {
    }
}
