<?php

namespace App\Contremarque\Bus\Command\CreateCarpetOrder;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class CreateCarpetOrderCommand implements Command
{
    /**
     * @param int $originalQuoteId
     * @param int $clonedQuoteId
     * @param int $contremarqueId
     * @param DateTimeInterface|null $createdAt
     */
    public function __construct(

        public readonly int                $originalQuoteId,
        public readonly int                $clonedQuoteId,
        public readonly int                $contremarqueId,
        public readonly ?DateTimeInterface $createdAt = null
    )
    {
    }
}