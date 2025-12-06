<?php

namespace App\User\Entity;

use App\Menu\Entity\Menu;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $name = null;
    #[ORM\Column(length: 60)]
    private ?string $entity = null;
    #[ORM\Column(length: 60)]
    private ?string $public_name = null;

    #[ORM\Column(length: 60)]
    private ?string $guard_name = null;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'permissions')]
    private Collection $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPublicName(): ?string
    {
        return $this->public_name;
    }

    public function setPublicName(string $public_name): static
    {
        $this->public_name = $public_name;

        return $this;
    }

    public function getGuardName(): ?string
    {
        return $this->guard_name;
    }

    public function setGuardName(string $guard_name): static
    {
        $this->guard_name = $guard_name;

        return $this;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): static
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->addPermission($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removePermission($this);
        }

        return $this;
    }
}
