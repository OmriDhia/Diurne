<?php

namespace App\Contremarque\Bus\Command\DeleteCarpetDesignOrder;

use App\Common\Bus\Command\Command;
use App\User\Entity\User;

class DeleteCarpetDesignOrderCommand implements Command
{
    public function __construct(
        private readonly int  $id,
        private readonly User $user
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
