<?php

declare(strict_types=1);

namespace App\MobileAppApi\Bus\Command\StockEntry\CreateStockEntry;

use App\Common\Bus\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateStockEntryCommand implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly int $rnId,
        #[Assert\NotBlank]
        public readonly string $location,
        public readonly ?int $userId = null
    ) {
    }
}
