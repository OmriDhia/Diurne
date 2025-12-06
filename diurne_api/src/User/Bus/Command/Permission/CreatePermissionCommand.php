<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Permission;

use App\Common\Bus\Command\Command;

class CreatePermissionCommand implements Command
{
    private string $name;
    private string $publicName;
    private string $gardName;
    private string $entity;

    public function __construct($name, $publicName, $gardName, $entity)
    {
        $this->setName($name);
        $this->setPublicName($publicName);
        $this->setGardName($gardName);
        $this->setEntity($entity);
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CreatePermissionCommand
    {
        $this->name = $name;

        return $this;
    }

    public function getPublicName(): string
    {
        return $this->publicName;
    }

    public function setPublicName(string $publicName): CreatePermissionCommand
    {
        $this->publicName = $publicName;

        return $this;
    }

    public function getGardName(): string
    {
        return $this->gardName;
    }

    public function setGardName(string $gardName): CreatePermissionCommand
    {
        $this->gardName = $gardName;

        return $this;
    }
}
