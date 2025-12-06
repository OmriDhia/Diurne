<?php

declare(strict_types=1);

namespace App\Contremarque\DTO;

use App\Common\DTO\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateQuoteDetailRequestDto extends BaseDto
{
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]  // Ensure locationId > 0
    public int $locationId;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]  // Ensure TarifId > 0
    public int $TarifId;

    public ?float $totalPriceRate = null;

    public ?bool $isValidated = null;

    public ?string $validatedAt = null;

    public ?int $wantedQuantity = null;

    public ?int $estimatedDeliveryTime = null;

    public ?bool $applyLargeProjectRate = null;

    public ?bool $applyProposedDiscount = null;

    public ?float $proposedDiscountRate = null;

    public ?bool $calculateFromTotalExcludingTax = null;

    #[Assert\GreaterThanOrEqual(1)]  // Ensure currencyId > 0
    public ?int $currencyId = null;

    public ?bool $inStockCarpet = null;

    public ?string $comment = null;

    #[Assert\Type('array')]
    public ?array $specificTreatmentIds = null;

    public ?string $rn = null;
}
