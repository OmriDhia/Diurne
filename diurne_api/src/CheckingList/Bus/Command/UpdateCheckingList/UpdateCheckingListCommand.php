<?php

namespace App\CheckingList\Bus\Command\UpdateCheckingList;

use App\Common\Bus\Command\Command;
use DateTimeInterface;

class UpdateCheckingListCommand implements Command
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $authorId = null,
        public readonly ?DateTimeInterface $date = null,
        public readonly ?DateTimeInterface $dateEndProd = null,
        public readonly ?string $comment = null
    ) {
    }
}
