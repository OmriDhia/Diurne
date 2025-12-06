<?php

namespace App\Workshop\Bus\Command\UpdateWorkshopOrder;

use DateTimeInterface;
use App\Common\Bus\Command\Command;

class UpdateWorkshopOrderCommand implements Command
{
    /**
     * @param int $id
     * @param string $reference
     * @param int $imageCommandId
     * @param int $workshopInformationId
     * @param DateTimeInterface|null $updatedAt
     */
    public function __construct(
        public readonly int                $id,
        public readonly string             $reference,
        public readonly int                $imageCommandId,
        public readonly int                $workshopInformationId,
        public readonly ?DateTimeInterface $updatedAt = null
    )
    {
    }

}