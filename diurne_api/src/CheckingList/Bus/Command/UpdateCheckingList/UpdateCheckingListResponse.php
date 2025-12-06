<?php

namespace App\CheckingList\Bus\Command\UpdateCheckingList;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\CheckingList;

class UpdateCheckingListResponse implements CommandResponse
{
    public function __construct(private readonly CheckingList $checkingList)
    {
    }

    public function toArray(): array
    {
        return $this->checkingList->toArray();
    }
}
