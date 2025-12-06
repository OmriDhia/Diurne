<?php

namespace App\Workshop\Bus\Command\CreateWorkshopOrder;

use DateTimeInterface;
use App\Common\Bus\Command\Command;

class CreateWorkshopOrderCommand implements Command
{
    /**
     * @param string $reference
     * @param int $imageCommandId
     * @param int $workshopInformationId
     * @param DateTimeInterface|null $createdAt
     * @param DateTimeInterface|null $updatedAt
     */
    public function __construct(
        public readonly string             $reference,
        public readonly int                $imageCommandId,
        public readonly int                $workshopInformationId,
        public readonly ?DateTimeInterface $createdAt = null,
        public readonly ?DateTimeInterface $updatedAt = null
    )
    {
    }

}