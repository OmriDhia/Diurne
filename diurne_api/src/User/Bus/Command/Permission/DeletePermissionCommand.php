<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\Command;

class DeletePermissionCommand implements Command
{
    private string $name;

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function setName(string $name): DeletePermissionCommand
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
