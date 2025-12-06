<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\Command;

class GivePermissionToProfileCommand implements Command
{
    private int $permissionId;
    private int $profileId;

    public function __construct($permissionId, $profileId)
    {
        $this->setPermissionId($permissionId);
        $this->setProfileId($profileId);
    }

    public function setPermissionId(int $permissionId): GivePermissionToProfileCommand
    {
        $this->permissionId = $permissionId;

        return $this;
    }

    public function getPermissionId(): int
    {
        return $this->permissionId;
    }

    public function setProfileId(int $profileId): GivePermissionToProfileCommand
    {
        $this->profileId = $profileId;

        return $this;
    }

    public function getProfileId(): int
    {
        return $this->profileId;
    }
}
