<?php

namespace App\CheckingList\Bus\Command\CreateCheckingList;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class CreateCheckingListCommand implements Command
{
    /**
     * @param int $workshopOrderId
     * @param int $authorId
     * @param DateTimeInterface|null $date
     * @param DateTimeInterface|null $dateEndProd
     * @param string $comment
     */
    public function __construct(
        public readonly int                $workshopOrderId,
        public readonly int                $authorId,

        public readonly ?DateTimeInterface $date = null,
        public readonly ?DateTimeInterface $dateEndProd = null,
        public readonly ?string            $comment = null
    )
    {
    }
}
