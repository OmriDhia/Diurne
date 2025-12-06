<?php

namespace App\CheckingList\Bus\Command\CreateCheckingList;

use App\Common\Bus\Command\CommandResponse;
use App\CheckingList\Entity\CheckingList;

class CreateCheckingListResponse implements CommandResponse
{
    public function __construct(private readonly CheckingList $checkingList)
    {
    }

    public function toArray(): array
    {
        return $this->checkingList->toArray();
    }
}
