<?php

namespace App\Contremarque\DTO;

use DateTimeImmutable;
use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateQuoteDetailRequestDto extends BaseDto
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\GreaterThanOrEqual(1)]  // Ensure locationId > 0
        public int $locationId,

        public ?string $reference,

        #[Assert\NotNull]
        #[Assert\GreaterThanOrEqual(1)]  // Ensure TarifId > 0
        public int $TarifId,

        #[Assert\NotNull]
        #[Assert\GreaterThanOrEqual(1)]  // Ensure currencyId > 0
        public int $currencyId,
        #[Assert\NotNull]
        public int $estimatedDeliveryTime,

        public ?string $totalPriceRate = null,

        public ?bool $isValidated = false,

        #[Assert\DateTime]
        public ?DateTimeImmutable $validatedAt = null,

        #[Assert\PositiveOrZero]
        public ?int $wantedQuantity = null,

        public ?bool $applyLargeProjectRate = null,
        public ?bool $applyProposedDiscount = null,
        public ?string $proposedDiscountRate = null,
        public ?bool $calculateFromTotalExcludingTax = null,
        public ?bool $inStockCarpet = null,
        public ?string $comment = null,

        #[Assert\Type('array')]
        public ?array $specificTreatmentIds = null,

        public ?string $rn = null,
    ) {}
}
